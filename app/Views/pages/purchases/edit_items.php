<?= $this->section('main') ?>
<div class="modal fade" id="editItemsModal" tabindex="-1" aria-labelledby="editItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemsModalLabel">Edit Purchase Items - <?= isset($purchase) ? $purchase->invoice : '' ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif ?>

                <form id="editItemsForm">
                    <input type="hidden" name="purchase_id" value="<?= $purchase ? $purchase->id : '' ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Unit Cost</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Tax %</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $idx = 1; ?>
                                <?php foreach ($items as $row): ?>
                                <tr>
                                    <td><?= $idx++ ?></td>
                                    <td>
                                        <?= $row->product ? $row->product->name : 'N/A' ?>
                                        <?= $row->product ? ' ('.$row->product->sku.')' : '' ?>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $row->id ?>][qty]" value="<?= $row->qty ?? 0 ?>" min="0" class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $row->id ?>][unit_cost]" value="<?= $row->unit_cost ?? 0 ?>" min="0" step="0.01" class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $row->id ?>][unit_price]" value="<?= $row->unit_price ?? 0 ?>" min="0" step="0.01" class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $row->id ?>][discount]" value="<?= $row->discount ?? 0 ?>" min="0" step="0.01" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $row->id ?>][tax]" value="<?= $row->tax ?? 0 ?>" min="0" step="0.01" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="items[<?= $row->id ?>][subtotal]" value="<?= $row->subtotal ?? 0 ?>" readonly class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-item-row" data-id="<?= $row->id ?>"><i class="fa fa-trash"></i> Remove</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" class="text-end"><strong>Total:</strong></td>
                                    <td><strong id="editItemsTotal"><?= isset($purchase) ? number_format($purchase->total_amount ?? 0, 2) : '0.00' ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editItemsForm" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(function () {
    // Calculate subtotal when qty, price, discount, or tax changes
    $("#editItemsForm").on("change keyup", "input[name^='items']", function () {
        var row = $(this).closest("tr");
        var id = $(this).attr("name").match(/items\[(\d+)\]\[(\w+)\]$/);
        
        if (!id) return;
        
        var qty = parseFloat(row.find("input[name*='qty']").val()) || 0;
        var unit_price = parseFloat(row.find("input[name*='unit_price']").val()) || 0;
        var discount = parseFloat(row.find("input[name*='discount']").val()) || 0;
        var tax = parseFloat(row.find("input[name*='tax']").val()) || 0;
        
        var subtotal = qty * unit_price - discount + tax;
        row.find("input[name*='subtotal']").val(subtotal.toFixed(2));
        
        // Recalculate total
        recalculateEditItemsTotal();
    });
    
    function recalculateEditItemsTotal() {
        var total = 0;
        $("#editItemsForm tbody tr").each(function () {
            var subtotal = parseFloat($(this).find("input[name*='subtotal']").val()) || 0;
            total += subtotal;
        });
        $("#editItemsTotal").html(total.toFixed(2));
    }
    
    // Handle remove row
    $(document).on("click", ".delete-item-row", function () {
        $(this).closest("tr").remove();
        recalculateEditItemsTotal();
    });
});
</script>
<?= $this->endSection() ?>