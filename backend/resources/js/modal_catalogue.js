// Mở Modal với dữ liệu từ item
function openEditModal(catalogueSlug) {
    // Lấy thông tin catalogue qua AJAX hoặc từ dữ liệu đã có sẵn
    $.ajax({
        url: '/catalogues/' + catalogueSlug + '/edit',  // URL lấy dữ liệu
        method: 'GET',
        success: function(response) {
            // Điền dữ liệu vào form trong modal
            $('#name').val(response.catalogue.name);
            $('#slug').val(response.catalogue.slug);
            
            // Cập nhật action của form để gửi dữ liệu đúng route
            $('#editCatalogueForm').attr('action', '/catalogues/' + catalogueSlug);
            
            // Hiển thị modal
            $('#editCatalogueModal').modal('show');
        }
    });
}
