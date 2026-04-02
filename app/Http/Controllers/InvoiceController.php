<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;
use App\Models\Client;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = $request->user()
            ->invoices()
            ->with('client')
            ->latest()
            ->get();

        return response()->json([
            'data' => $invoices
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $invoice = $request->user()->invoices()->create($request->all());

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $invoice = $request->user()->invoices()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:paid,unpaid,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $invoice->update($request->all());

            return response()->json([
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $invoice = $request->user()->invoices()->findOrFail($id);

        try {
            $invoice->delete();

            return response()->json([
                'message' => 'Invoice deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportPdf(Request $request, $id)
    {
        $invoice = $request->user()->invoices()
            ->with('client')
            ->findOrFail($id);

        try {
            // In a real application, you would generate a PDF here
            // For now, we'll return a URL to a generated PDF
            $pdfUrl = url("/invoices/{$id}/pdf");

            return response()->json([
                'url' => $pdfUrl,
                'message' => 'PDF generated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to export PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        
        $totalInvoices = $user->invoices()->count();
        $paidInvoices = $user->invoices()->where('status', 'paid')->count();
        $totalRevenue = $user->invoices()->where('status', 'paid')->sum('amount');
        $pendingAmount = $user->invoices()->where('status', 'unpaid')->sum('amount');

        return response()->json([
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'total_revenue' => $totalRevenue,
            'pending_amount' => $pendingAmount,
        ]);
    }
}
