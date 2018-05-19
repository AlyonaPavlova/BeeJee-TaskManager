<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css">
    <link rel="stylesheet" href="../../css/main_view.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fa d-inline fa-lg fa-cloud"></i><b> Task manager</b></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbar2SupportedContent"
                aria-controls="navbar2SupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center justify-content-end" id="navbar2SupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa d-inline fa-lg fa-user"></i><span
                                id="username">Contacts</span></a>
                </li>
            </ul>
            <button id="signUp" class="btn navbar-btn ml-2 text-white btn-secondary" data-toggle="modal"
                    data-target="#sign-up">
                <i class="fa d-inline fa-lg fa-user-plus"></i> Sign up
            </button>
            <button id="signIn" class="btn navbar-btn ml-2 text-white btn-secondary" data-toggle="modal"
                    data-target="#sign-in">
                <i class="fa d-inline fa-lg fa-user-circle-o"></i> Sign in
            </button>
            <button id="signOut" class="btn navbar-btn ml-2 text-white btn-secondary">
                <i class="fa d-inline fa-lg fa-sign-out"></i> Sign Out
            </button>
            <button id="addTask" class="btn navbar-btn ml-2 text-white btn-secondary" data-toggle="modal"
                    data-target="#add-task">
                <i class="fa d-inline fa-lg fa-tasks"></i> Add Task
            </button>
        </div>
    </div>
</nav>

<!-- Modal Window SignUp -->
<div class="modal" id="sign-up">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gradient">
            <div>
                <button type="button" class="close pr-2" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body card p-3 bg-gradient border-0">
                <div class="card-body">
                    <h1 class="mb-4">Sign up</h1>
                    <form class="form" id="signUpForm">
                        <div class="form-group">
                            <label>Email address</label>
                            <input required name="email" type="email" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label>Login</label>
                            <input required name="login" type="text" class="form-control" placeholder="Enter login">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input required name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                        <span id="signUpErrorText"></span><br>
                        <button type="submit" class="btn btn-secondary" id="register">Sign up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  Modal Window SignIn -->
<div class="modal" id="sign-in">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gradient">
            <div>
                <button type="button" class="close pr-2" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body card p-3 bg-gradient border-0">
                <div class="card-body">
                    <h1 class="mb-4">Sign in</h1>
                    <form id="loginForm">
                        <div class="form-group">
                            <label>Login</label>
                            <input required name="login" type="text" class="form-control" placeholder="Enter login">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input required name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                        <span id="loginError"></span><br>
                        <button type="submit" class="btn btn-secondary">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Window New Task Adding -->
<div class="modal" id="add-task">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="p-1">
                <button type="button" class="close pr-2" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
            </div>
            <div class="py-5 opaque-overlay"
                 style="background-image: url('../../images/milk.jpg');background-repeat: no-repeat; background-size: cover;">
                <div class="container">
                    <div class="row text-white">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <h1 class="text-gray-dark">Adding a task</h1>
                            <p class="lead mb-4">You can add a description of the task, and also attach an image</p>
                            <form id="addTaskForm" enctype="multipart/form-data" class="text">
                                <div class="form-group">
                                    <label class="text-white">Task description</label>
                                    <input type="text" name="task" class="form-control taskDescription"
                                           placeholder="Enter a description of the task">
                                </div>

                                <div class="form-group">
                                    <label class="text-white">Image</label>
                                    <input type="file" accept="image/*" id="inputFile" name="picture"
                                           class="form-control-file">
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="previewBtn">
                        <div class="col-md-3"></div>
                        <div class="col-md-4 mb-3">
                            <a class="btn btn-info" href="#" id="trig">Preview</a>
                        </div>
                    </div>
                    <div class="row bg-white pt-3 pb-3" id="previewAddTask">
                        <div class="col-md-12">
                            <div class="list-group">
                                <li class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img id="image_upload_preview" src="http://placehold.it/320x240"
                                                 alt="your image" width="320" height="240">
                                        </div>
                                        <div class="col-md-6">
                                            <h2 class="text-primary pt-3">Task № 0<span class="taskId"></span></h2>
                                            <p id="taskDescription"></p>
                                            <p>User: <span id="previewLogin" class="taskAuthor">  taskLogin  </span></p>
                                            <p> Email: <span id="previewMail" class="taskAuthor"> taskEmail </span></p>
                                        </div>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sorting -->
<div class="py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline">
                    <li class="list-inline-item">Sort by: </li>
                    <li class="list-inline-item"><a href="#" class="sortingType">ID</a></li>
                    <li class="list-inline-item"> / </li>
                    <li class="list-inline-item"><a href="#" class="sortingType">Author</a></li>
                    <li class="list-inline-item"> / </li>
                    <li class="list-inline-item"><a href="#" class="sortingType">Email</a></li>
                    <li class="list-inline-item"> / </li>
                    <li class="list-inline-item"><a href="#" class="sortingType">Status</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- TASK LIST -->
<div class="py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="list-group" id="myList">
                    <?php
                        foreach ($data as $row) {
                            $check_status = '';
                            if ($row["isdone"] === "1"){
                                $check_status = 'checked="checked"';
                            }
                            echo '<li class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <img src="images/userpic/'.$row["imgpath"].'" width="320" height="240">
                                        </div>
                                        <div class="col-md-6">
                                            <h2 class="text-primary pt-3">Task №<span class="taskId"> '.$row["id"].'  </span></h2>
                                            <p>  '.$row["description"].'  </p>
                                            <p>User: <span class="taskAuthor">  '.$row["login"] .'</span></p> 
                                            <p> Email:   '.$row["email"].'  </p>
                                        </div> 
                                        <div class="col-md-1"> 
                                            <p class="campaign-signup with-tooltip mb-5"> 
                                                <label> 
                                                    <input type="checkbox" class="checkbox addStatus"  '.$check_status.'>
                                                    <span class="label-text"> 
                                                        <a href="#" class="icon-question-sign" 
                                                            data-original-title="Mark the task is done" 
                                                            data-placement="right" data-toggle="tooltip">
                                                        </a>
                                                    </span> 
                                                </label> 
                                            </p> 
                                            <p>
                                                <button type="button" class="close mt-5" data-dismiss="modal" aria-label="Close"> 
                                                    <span class="icon-trash" aria-hidden="true"></span>
                                                </button>
                                            </p> 
                                        </div> 
                                    </div> 
                                </li>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="pagination">
                    <li class="page-item" id="paginationShevronLeft">
                        <a id="prevPageBtn" class="page-link" href="#" aria-label="Previous"> <span
                                    aria-hidden="true">«</span> <span class="sr-only">Previous</span> </a>
                    </li>

                    <?php
                    while ($page++ < $page_count) {
                        echo "<li class='page-item'><a href='#' class='page-link page-number'>$page</a></li>";
                    }
                    ?>

                    <li class="page-item">
                        <a id="nextPageBtn" class="page-link" href="#" aria-label="Next"> <span
                                    aria-hidden="true">»</span> <span class="sr-only">Next</span> </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center align-self-center">
                <p class="mb-5"><strong>BeeJee Ninjas</strong>
                    <br>Vasily Kasiyan street, 2/1
                    <br> Kyiv, Ukraine
                    <br> <abbr title="Phone">P:</abbr> (123) 456-7890 </p>
                <div class="my-0 row">
                    <div class="col-4">
                        <a href="https://www.facebook.com" target="_blank"><i class="fa fa-3x fa-facebook"></i></a>
                    </div>
                    <div class="col-4">
                        <a href="https://twitter.com" target="_blank"><i class="fa fa-3x fa-twitter"></i></a>
                    </div>
                    <div class="col-4">
                        <a href="https://www.instagram.com" target="_blank"><i class="fa fa-3x fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2544.3199117976883!2d30.459887967309403!3d50.37923463409873!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4c8fcaff2a83d%3A0x4d6350338f1890ad!2z0YPQuy4g0JLQsNGB0LjQu9C40Y8g0JrQsNGB0LjRj9C90LAsIDIvMSwg0JrQuNC10LIsINCj0LrRgNCw0LjQvdCwLCAwMjAwMA!5e0!3m2!1sru!2sru!4v1520702360134"
                        width="450" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="../../js/main.js"></script>
</body>

</html>

