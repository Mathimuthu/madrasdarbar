<div class="modal-edit-order">

    <div class="wrapper">

        <h2>Manage Order</h2>

        <i class='bx bx-x' id="modal-close"></i>

        <div class="content">

            <div class="container">

                <form action="" method="POST" id="order-edit-form">
                    @method('PUT')
                    @csrf

                    <div class="order-status">
                        <span class="label">Order Status</span>
                        <div class="status">
                            <span class="data">Pending</span> <!-- This will be updated by JS -->
                            <span class="edit" id="edit-order-status">Change to Completed</span>
                        </div>
                    </div>

                    <!-- Add hidden input for order status -->
                    <input type="hidden" name="order_status" id="order-status-input" value="">

                    <div class="food-list-order">
                        <span class="label">Food Order</span>
                        <div class="list">
                            <!-- This will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="button-section">
                        <input type="submit" value="Update Status" disabled>
                        <button type="button" class="cancel"><span>Cancel</span></button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
 <script>
        // Pass Laravel route to JavaScript
        const updateOrderRoute = "{{ route('update-order', ['id' => ':id']) }}";
    </script>