Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};


$(document).ready(function () {
    var request;
    var isSigned = false;
    var signInErr = $("#loginError");
    var signUpErr = $("#signUpErrorText");
    var loginForm = $("#loginForm");
    var signUpForm = $("#signUpForm");
    onSignOut();
    checkSessionLogin();
    $("#previewAddTask").hide();
    localStorage.setItem("page", 1);    //  Страница по умолчанию
    localStorage.setItem("sort_type", "id");    //  Тип сортировки по умолчанию
    localStorage.setItem("sort_direction", "DESC");    //  Направление сортировки по умолчанию


    $("#sign-in").on('shown.bs.modal', function () {
        // Обнуляем ошибку
        signInErr.text('');
    });


    $("#sign-up").on('shown.bs.modal', function () {
        // Обнуляем ошибку
        signUpErr.text('');
    });


    loginForm.submit(function (event) {
        event.preventDefault();
        // Обрубаем активные запросы
        abortRequest();
        var $form = $(this);
        var $inputs = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();
        $inputs.prop("disabled", true);
        request = $.ajax({
            type: 'POST',
            url: "/handler/auth",
            data: serializedData
        });
        request.done(function (response) {
            if (response["status"] === "okLogin") {
                onSignIn(response);
                saveUserDataOnAuth(response);
                $(".modal").modal("hide");
            } else if (response["status"] === "pwderror") {
                signInErr.text("Wrong password");
            } else if (response["status"] === "loginerror") {
                signInErr.text("Login does not exist");
            }
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        request.always(function () {
            $inputs.prop("disabled", false);
        });
    });


    // Отлавливаем отправку формы регистрации
    signUpForm.submit(function (event) {
        event.preventDefault();
        // Обрубаем активные запросы
        abortRequest();
        var $form = $(this);
        var $inputs = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();
        $inputs.prop("disabled", true);
        request = $.ajax({
            type: 'POST',
            url: "/handler/register",
            data: serializedData
        });
        request.done(function (response) {
            signUpErr.text(response);
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        request.always(function () {
            $inputs.prop("disabled", false);
        });
    });


    // Предпросмотр задачи
    $('#previewBtn').on('click', function () {
        var taskLogin = localStorage.getItem('login');
        var taskEmail = localStorage.getItem('email');

        $("#previewLogin").text(taskLogin);
        $("#previewMail").text(taskEmail);
        $('#taskDescription').text($(".taskDescription").val());

        if (!$('#previewAddTask').is(":visible")) {
            $('#previewAddTask').show();
            $('#addTaskForm').hide();
        } else {
            $('#previewAddTask').hide();
            $('#addTaskForm').show();
        }
    });


    // Добавление задачи
    $("#addTaskForm").submit(function (event) {
        event.preventDefault();
        abortRequest();

        var formData = new FormData(this);
        if (isSigned) {
            formData.append('login', $("#username").text().replace(' ', ''));
        } else {
            formData.append('login', 'Anonymous');
        }
        request = $.ajax({
            type: 'POST',
            url: "/main/add_task",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        request.done(function (response, textStatus, jqXHR) {
            getTasks();
            $(".modal").modal("hide");
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
    });


    // Выход
    $("#signOut").on("click", function () {
        signOutFromServer();
    });


    // Меняем интерфейс при входе
    function onSignIn(responce) {
        $("#signIn").hide();
        $("#signUp").hide();
        $("#signOut").show();
        $(".navbar-nav").show();
        $("#username").text(" " + responce['userData'][Object.keys(responce['userData'])]['login']);
        isSigned = true;
    }

    // Меняем интерфейс при выходе
    function onSignOut() {
        $("#signIn").show();
        $("#signUp").show();
        $("#signOut").hide();
        $(".navbar-nav").hide();
        isSigned = false;
        resetLocalStorage();
    }

    // Проверяем сессию, если активна - входим
    function checkSessionLogin() {
        request = $.ajax({
            type: 'POST',
            url: "/handler/auth"
        }).done(function (response, textStatus, jqXHR) {
            if (response["status"] === "okSession") {
                onSignIn(response);
                saveUserDataOnAuth(response);
                $(".modal").modal("hide");
            } else {
                resetLocalStorage();
            }
        });
    }

    // Сохраняем данные пользователя в local storage при логине
    function saveUserDataOnAuth(userData) {

        var login = userData['userData'][0]['login'];
        var email = userData['userData'][0]['email'];

        localStorage.setItem('login', login);
        localStorage.setItem('email', email);

    }


    function resetLocalStorage() {
        localStorage.setItem('login', 'Anonymous');
        localStorage.setItem('email', 'anonymously@anonymous@an');
    }


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#previewAddTask").on('show', '#image_upload_preview', function () {
                });
                $('#image_upload_preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


    $("#inputFile").change(function () {
        readURL(this);
    });


    // Выход с закрытием сессии
    function signOutFromServer() {
        request = $.ajax({
            type: 'POST',
            url: "/handler/out",
            data: "login=" + $("#username").text().replace(' ', '')
        }).done(function (response, textStatus, jqXHR) {
            onSignOut();
        });
    }


    function getTasks() {
        var data = {'page' : getPageNumber(), 'sort_type' : getSortType(), 'sort_direction' : getSortOrder()};
        request = $.ajax({
            type: 'POST',
            url: "/main/get_task",
            data: data
        }).done(function (response) {
            $('#myList').empty();
            $('#myList').append(response);
        });
    }


    // Возвращаем сохраненное значение направления сортировки
    function getSortOrder() {
        return localStorage.getItem('sort_direction');
    }


    // Возвращаем сохраненное значение типа сортировки
    function getSortType() {
        return localStorage.getItem('sort_type');
    }


    // Возвращаем сохраненное значение страницы
    function getPageNumber() {
        return localStorage.getItem('page');
    }


    // Сортировка
    $(".sortingType").on('click', function () {
        getSortOrder() === "DESC" ? localStorage.setItem('sort_direction', 'ASC') : localStorage.setItem('sort_direction', 'DESC');

        switch ($(this).text()){
            case 'ID':
                localStorage.setItem("sort_type", "id");
                break;
            case 'Author':
                localStorage.setItem("sort_type", "login");
                break;
            case 'Email':
                localStorage.setItem("sort_type", "email");
                break;
            case 'Status':
                localStorage.setItem("sort_type", "isdone");
                break;
        }
        getTasks();
    });


    // Удалить задание из списка
    $("#myList").on('click', '.close', function () {
        abortRequest();
        var currentUser = $("#username").text().replace(' ', '');
        var taskId = $(this).closest(".row").find(".taskId").text();
        var taskAuthor = $(this).closest(".row").find(".taskAuthor").text();

        var data = {'current_user': currentUser, 'task_id': taskId, 'task_author': taskAuthor};
        request = $.ajax({
            type: 'POST',
            url: "/main/delete_task",
            data: data
        });
        request.done(function (response) {
            if (response === "ok") {
                getTasks();
            } else if (response === "error") {
                alert("Task deleting error")
            }
        });
    });

    function abortRequest() {
        if (request) {
            request.abort();
        }
    }


    // Отметить задание выполненным
    $("#myList").on('click', ".addStatus", function (event) {
        abortRequest();

        var state;
        var taskId = $(this).closest(".row").find(".taskId").text();
        if ($(this)[0].checked) {
            state = "1";
        } else {
            state = "0";
        }

        var data = {'state': state, 'id': taskId};
        request = $.ajax({
            type: 'POST',
            url: "/handler/task_status",
            data: data
        });
        request.done(function (response, textStatus, jqXHR) {
            console.log(response);
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
    });


    $('.page-number').on('click', function () {
        localStorage.setItem('page', $(this).text());
        getTasks();
    });

});