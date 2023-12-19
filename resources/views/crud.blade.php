@extends('app')
@section('content')
<div class="alert alert-success d-none" role="alert" id="successAlert"></div>
    <div class="alert alert-danger d-none" role="alert" id="dangerAlert"></div>
    <!-- Page Heading -->
    <div>
    
    <button class="btn btn-primary" id="addBookFormBtn">Add</button>
    <button class="btn btn-danger" id="cancelBookFormBtn">Clear</button>

    <div class="user w-25 mt-2 p-2 border" id="addBookForm" >
        <div class="form-group">
            <input type="text" class="form-control form-control-user" id="title"
                placeholder="Book Title" name="title">
        </div>
        <div class="form-group">
            <input type="text" class="form-control form-control-user" id="author"
                placeholder="Author Name" name="author">
        </div>
        <button class="btn btn-success btn-user btn-block" id="addBookBtn">
            Add Book
        </button>
        
    </div>

    <div class="card shadow mb-4 my-2">
        <div class="card-body">
            <div class="alert alert-success d-none" role="alert" id="successAlert">
            Book has been updated successfully!
            </div>
            <div class="alert alert-danger d-none" role="alert" id="dangerAlert">
            Something went wrong!
            </div>
            <div class="table-responsive" >
                <table class="table table-bordered" id="BookTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($books as $key => $book){
                        ?>
                        <tr>
                            <td id="cat<?php echo $book->id; ?>">{{++$key}}</td>
                            <td class="title">{{$book->title}}</td>
                            <td class="author">{{$book->author}}</td>
                            <td class="editBook">
                                <button class="btn btn-success" onclick="editBookHandler(<?php echo $book->id?>, '<?php echo $book->title?>', '<?php echo $book->author?>')">Edit</button>
                                <button class="btn btn-danger" onclick="deleteBookHandler(<?php echo $book->id?>)">Delete</button>
                            </td>
                            <td class="d-none updateBook">
                                <button class="btn btn-success " onclick="updateBookHandler(<?php echo $book->id?>)">Update</button>
                                <button class="btn btn-danger" onclick="cancelEditHandler(<?php echo $book->id?>)">Cancel</button>
                            </td>
                            
                        </tr>
                        <input type="hidden" id="lastElement" value={{++$key}} />
                        <?php
                            }
                        ?>
                        
                    </tbody>
                </table>
                <div id="pagination" class="pagination">
                    <button class="btn btn-primary" id="prev">Previous</button>
                    <span id="page" class="m-2">1</span>
                    <button class="btn btn-primary" id="next">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection