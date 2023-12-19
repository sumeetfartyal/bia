<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('includes.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('includes.navbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            
            @include('includes.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('js/sb-admin-2.min.js') }}"></script>

    <script>

        $(document).ready(function(){
            var table, tableRows, rowsPerPage, currentPage, totalPages;
            $('#addBookForm').hide()
            pagination();
        })

        function pagination(){
            table = $('#BookTable');
            tableRows = table.find('tbody tr');
            rowsPerPage = 3; 
            currentPage = 1; 
            
            $('#page').text(currentPage);
            totalPages = Math.ceil(tableRows.length / rowsPerPage);

            showRows(currentPage);

        }

        function showRows(page) {
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;
            tableRows.hide();
            tableRows.slice(start, end).show();
        }

        // Handle "Next" button click
        $('#next').on('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                showRows(currentPage);
                $('#page').text(currentPage);
            }
        });

        // Handle "Previous" button click
        $('#prev').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                showRows(currentPage);
                $('#page').text(currentPage);
            }
        });

        $('#addBookFormBtn').click(function(){
            $('#cancelBookFormBtn').show();
            $('#addBookForm').show();
        })

        $('#cancelBookFormBtn').click(function(){
            $('#cancelBookFormBtn').hide();
            $('#addBookForm').hide();
        })

        $('#addBookBtn').click(function (){

            $('#successAlert').addClass('d-none');
            $('#dangerAlert').addClass('d-none');

            let title = $('#title').val();
            let author = $('#author').val();

            var settings = {
                "url": "api/add-book",
                "method": "post",
                "headers": {
                    "Accept-Language": "application/json",
                },
                "data": {
                    "title": title,
                    "author": author,
                }
            };
            $.ajax(settings).done(function (response) {

                if(response.error){
                    $('#dangerAlert').removeClass('d-none');
                    $('#dangerAlert').html(response.error);
                    
                }else{

                    let rowCount = $('#BookTable tr').length; 
                    $('#BookTable tr:last').after(
                        `<tr>
                            <td id="cat`+ response.data.id +`">`+rowCount+`</td>
                            <td class="title">`+title+`</td>
                            <td class="author">`+author+`</td>
                            <td class="editBook">
                                <button class="btn btn-success" onclick="editBookHandler(`+ response.data.id +`, '`+ response.data.title +`', '`+  response.data.author+`')">Edit</button>
                                <button class="btn btn-danger" onclick="deleteBookHandler(`+ response.data.id +`)">Delete</button></td><td class="d-none updateBook">
                                <button class="btn btn-success " onclick="updateBookHandler(`+ response.data.id +`)">Update</button>
                                <button class="btn btn-danger" onclick="cancelEditHandler(`+ response.data.id +`)">Cancel</button>
                            </td>
                        </tr>`
                    );

                    $('#successAlert').removeClass('d-none');
                    $('#successAlert').html(response.success);
                    $('#addBookForm').hide()
                    pagination();
                }
                
                
            }).fail(function(error){
                let errors = error.responseJSON.errors;
                let errorList = "";
                if(errors.title){
                    errorList += "<li>"+ errors.title +"</li>"
                }
                if(errors.author){
                    errorList += "<li>"+ errors.author +"</li>"
                }
                $('#dangerAlert').removeClass('d-none');
                $('#dangerAlert').html(errorList);
            });
        })

        function editBookHandler(id, title, author){

            
            $('#cat'+id).siblings('.title').html("<input type='text' value='"+ title +"' />");
            $('#cat'+id).siblings('.author').html("<input type='text' value='"+ author +"' />");

            $('#cat'+id).siblings('.updateBook').removeClass('d-none');

            $('#cat'+id).siblings('.editBook').addClass('d-none');
            
        }

        function cancelEditHandler(id){

            let title = $('#cat'+id).siblings('.title').find('input').val();
            let author = $('#cat'+id).siblings('.author').find('input').val();

            $('#cat'+id).siblings('.title').html(title);
            $('#cat'+id).siblings('.author').html(author);

            $('#cat'+id).siblings('.updateBook').addClass('d-none');

            $('#cat'+id).siblings('.editBook').removeClass('d-none');
        }
        
        function updateBookHandler(id){
            // let id = $('#editCategoryId').val();
            let title = $('#cat'+id).siblings('.title').find('input').val();
            let author = $('#cat'+id).siblings('.author').find('input').val();
            let settings = {
                "url": "api/edit-book/"+id,
                "method": "put",
                "headers": {
                    "Accept-Language": "application/json",
                },
                "data": {
                    "title": title,
                    "author": author,
                }
            }
            $.ajax(settings).done(function(response){
                if(response.success){
                    $('#updateCategoryForm').addClass('d-none');
                    $('#BookTable').removeClass('d-none');
                    

                    $('#cat'+id).siblings('.title').html(title);
                    $('#cat'+id).siblings('.author').html(author);
                    $('#successAlert').removeClass('d-none');
                    $('#successAlert').html(response.success);
                    
                }else if(response.error){
                    $('#dangerAlert').removeClass('d-none');
                    $('#dangerAlert').html(response.error);
                }
            }).fail(function(error){
                let errors = error.responseJSON.errors;
                let errorList = "";
                if(errors.title){
                    errorList += "<li>"+ errors.title +"</li>"
                }
                if(errors.author){
                    errorList += "<li>"+ errors.author +"</li>"
                }
                $('#dangerAlert').removeClass('d-none');
                $('#dangerAlert').html(errorList);
            });
        }

        function deleteBookHandler(id){

            let confirmDelete = confirm("Do you really want to delete this category?");
            if(confirmDelete){
                let settings = {
                    "url": "api/delete-book/"+id,
                    "method": "delete",
                    "headers": {
                        "Accept-Language": "application/json",
                    },
                }
                $.ajax(settings).done(function(response){
                    if(response.success){
                        $('#cat'+id).parent().remove();

                        $('#successAlert').removeClass('d-none');
                        $('#successAlert').html(response.success);
                        updateFirstTdValues()
                        
                    }else if(response.error){
                        $('#dangerAlert').removeClass('d-none');
                        $('#dangerAlert').html(response.error);
                    }
                })
            }
        }

        function updateFirstTdValues() {
            var table = document.getElementById("BookTable");
            var rows = table.rows;

            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var firstCell = row.cells[0];
                firstCell.textContent = (i).toString();
            }
        }

        $('#cancelEditCategoryBtn').click(function(){
            $('#updateCategoryForm').addClass('d-none');
            $('#BookTable').removeClass('d-none');
        })
    </script>
</body>

</html>