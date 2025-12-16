<?php

namespace App\Http\Controllers\Owner;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Http\Controllers\Controller;

use App\Models\Order;

use App\Models\Restaurant;

use App\Models\User;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class OrderController extends Controller

{

    public function index(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        // Start the query
        $query = Order::with(['customer', 'rider', 'items.menuItem'])
            ->where('restaurant_id', $restaurant->id);

        // Apply status filter if not 'all'
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        // Get counts for all statuses
        $statuses = ['pending', 'preparing', 'ready', 'on_the_way', 'delivered', 'cancelled'];
        $statusCounts = [];

        foreach ($statuses as $status) {
            $statusCounts[$status] = Order::where('restaurant_id', $restaurant->id)
                ->where('status', $status)
                ->count();
        }

        return view('owner.orders.index', compact('orders', 'statusCounts', 'restaurant'));
    }



    public function show(Order $order)

    {

        // Authorization - ensure the order belongs to owner's restaurant

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        if ($order->restaurant_id !== $restaurant->id) {

            abort(403, 'Unauthorized access to this order.');
        }



        $order->load(['customer', 'rider', 'items.menuItem', 'messages.sender']);



        return view('owner.orders.show', compact('order', 'restaurant'));
    }



    // app/Http\Controllers\Owner\OrderController.php

    public function updateStatus(Request $request, Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Prevent updates for delivered or cancelled orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot update status for ' . ucfirst($order->status) . ' orders.');
        }

        // NEW: Prevent marking as delivered if GCash payment is not verified
        if (
            $request->status === 'delivered' &&
            $order->payment_method === 'gcash' &&
            $order->gcash_payment_status !== 'verified'
        ) {
            return redirect()->back()->with('error', 'Cannot mark order as delivered: GCash payment is not yet verified.');
        }

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,on_the_way,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        try {
            $order->update(['status' => $newStatus]);

            // Log status change
            \Log::info("Order #{$order->order_number} status changed from {$oldStatus} to {$newStatus}");

            return redirect()->back()->with('success', "Order status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . " successfully!");
        } catch (\Exception $e) {
            \Log::error("Failed to update status for Order #{$order->order_number}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status. Please try again.');
        }
    }



    public function assignRider(Request $request, Order $order)

    {

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        if ($order->restaurant_id !== $restaurant->id) {

            abort(403, 'Unauthorized access to this order.');
        }



        $request->validate([

            'rider_id' => 'required|exists:users,id'

        ]);



        // Verify the user is actually a rider

        $rider = User::where('id', $request->rider_id)

            ->where('role', 'rider')

            ->first();



        if (!$rider) {

            return redirect()->back()->with('error', 'Selected user is not a rider.');
        }



        try {

            $oldRider = $order->rider;

            $order->update(['rider_id' => $request->rider_id]);



            // Log the assignment

            \Log::info("Rider {$rider->name} assigned to Order #{$order->order_number}");



            $message = $oldRider ?

                "Rider changed to {$rider->name} successfully!" :

                "Rider {$rider->name} assigned successfully!";



            return redirect()->route('owner.orders.show', $order)

                ->with('success', $message);
        } catch (\Exception $e) {

            \Log::error("Failed to assign rider to Order #{$order->order_number}: " . $e->getMessage());

            return redirect()->back()

                ->with('error', 'Failed to assign rider. Please try again.');
        }
    }



    // Add delete method

    public function destroy(Order $order)

    {

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        if ($order->restaurant_id !== $restaurant->id) {

            abort(403, 'Unauthorized access to this order.');
        }



        // Only allow deletion of certain statuses for safety

        if (!in_array($order->status, ['pending', 'cancelled'])) {

            return redirect()->back()->with('error', 'Cannot delete orders that are already in progress or delivered.');
        }



        try {

            $orderNumber = $order->order_number;



            // Delete related records first

            $order->items()->delete();

            $order->messages()->delete();



            // Then delete the order

            $order->delete();



            return redirect()->route('owner.orders.index')

                ->with('success', "Order #{$orderNumber} deleted successfully!");
        } catch (\Exception $e) {

            Log::error("Failed to delete Order #{$order->order_number}: " . $e->getMessage());



            return redirect()->back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }





    // Add method to show rider assignment form

    public function showAssignRiderForm(Order $order)

    {


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        if ($order->restaurant_id !== $restaurant->id) {

            abort(403, 'Unauthorized access to this order.');
        }



        $availableRiders = User::where('role', 'rider')

            ->where('status', 'active')

            ->get();



        return view('owner.orders.assign-rider', compact('order', 'availableRiders'));
    }



    private function logStatusChange(Order $order, $oldStatus, $newStatus)

    {

        // You can implement logging, notifications, or other business logic here

        // For example, send notification to customer when status changes

        Log::info("Order #{$order->order_number} status changed from {$oldStatus} to {$newStatus}");
    }



    public function getTodayOrders()

    {

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        $todayOrders = Order::with(['customer', 'items.menuItem'])

            ->where('restaurant_id', $restaurant->id)

            ->today()

            ->latest()

            ->get();



        return response()->json($todayOrders);
    }



    public function getActiveOrders()

    {

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        $activeOrders = Order::with(['customer', 'rider', 'items.menuItem'])

            ->where('restaurant_id', $restaurant->id)

            ->active()

            ->latest()

            ->get();



        return response()->json($activeOrders);
    }

    /**

     * View GCash receipt

     */

    /**

     * View GCash receipt

     */

    /**

     * View GCash receipt

     */

    public function viewGcashReceipt(Order $order, Request $request)

    {

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();



        if ($order->restaurant_id !== $restaurant->id) {

            abort(403, 'Unauthorized access to this order.');
        }



        if (!$order->gcash_receipt_path) {

            abort(404, 'GCash receipt not found.');
        }



        try {

            $filePath = storage_path('app/public/' . $order->gcash_receipt_path);



            // Check if file exists

            if (!file_exists($filePath)) {

                abort(404, 'GCash receipt file not found.');
            }



            $mimeType = mime_content_type($filePath);



            // Check if download is requested

            if ($request->has('download')) {

                return response()->download($filePath, "gcash-receipt-{$order->order_number}.jpg");
            }



            // For modal display, return the image file

            return response()->file($filePath, [

                'Content-Type' => $mimeType,

                'Cache-Control' => 'no-cache, no-store, must-revalidate',

                'Pragma' => 'no-cache',

                'Expires' => '0'

            ]);
        } catch (\Exception $e) {

            Log::error("Error viewing GCash receipt for Order #{$order->order_number}: " . $e->getMessage());

            abort(500, 'Error loading GCash receipt.');
        }
    }

    /**

     * Update GCash payment status

     */

    public function updateGcashStatus(Request $request, Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Prevent GCash updates for delivered or cancelled orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot update payment status for ' . ucfirst($order->status) . ' orders.');
        }

        $request->validate([
            'gcash_payment_status' => 'required|in:pending,verified,rejected'
        ]);

        $oldStatus = $order->gcash_payment_status;
        $newStatus = $request->gcash_payment_status;

        try {
            $order->update([
                'gcash_payment_status' => $newStatus
            ]);

            // If GCash is rejected, you might want to update order status
            if ($newStatus === 'rejected') {
                $order->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => 'GCash payment rejected'
                ]);
            }

            // Log the status change
            Log::info("GCash payment status for Order #{$order->order_number} changed from {$oldStatus} to {$newStatus}");

            return redirect()->back()->with('success', 'GCash payment status updated successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to update GCash status for Order #{$order->order_number}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update GCash payment status. Please try again.');
        }
    }
    /**
     * Generate and view official receipt
     */
    public function generateReceipt(Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load all necessary relationships
        $order->load(['customer', 'rider', 'items.menuItem', 'restaurant']);

        // Get restaurant info (owner info)
        $owner = Auth::user();

        return view('owner.orders.receipt', compact('order', 'restaurant', 'owner'));
    }

    /**
     * Download receipt as PDF (Direct DomPDF approach)
     */
    public function downloadReceipt(Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load all necessary relationships
        $order->load(['customer', 'rider', 'items.menuItem', 'restaurant']);
        $owner = Auth::user();

        // Create DomPDF instance
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultPaperSize', 'A4');
        $options->set('defaultPaperOrientation', 'portrait');

        $dompdf = new Dompdf($options);

        // Load HTML content - use a simpler view for PDF
        $html = view('owner.orders.receipt-pdf', compact('order', 'restaurant', 'owner'))->render();
        $dompdf->loadHtml($html);

        // Render PDF
        $dompdf->render();

        // Generate filename
        $filename = "Receipt-{$order->order_number}-" . now()->format('Y-m-d') . ".pdf";

        // Stream the PDF
        return $dompdf->stream($filename, [
            "Attachment" => true // Force download
        ]);
    }
}
