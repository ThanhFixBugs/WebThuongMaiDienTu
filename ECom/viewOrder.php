<html>
    <body>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Chi tiết</h5>
                <button type="button" class="btn-close btn bg-darkblue text-light" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <table class="table" id="product_table">
                    <tr>
                        <th>Mã SP</th>
                        <th>Tên SP</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-25" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
    </body>
    <script>
        $('#detailModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);

        const products = button.data('orders');

        const productTable = $('#product_table');

        products.forEach(product => {
            const newRow = $('<tr></tr>').addClass('product-row');
            newRow.append($('<td>').text(product['id']));
            newRow.append($('<td>').text(product['title']));
            newRow.append($('<td>').text(product['quantity']));
            newRow.append($('<td>').append($('<trong>').addClass('price').text('$' + product['product_price'])));

            productTable.append(newRow);
        });
    });
    </script>
</html>