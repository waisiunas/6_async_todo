<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ./');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="./assets/img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

    <title>Tasks</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="./assets/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php require_once './partials/sidebar.php' ?>

        <div class="main">
            <?php require_once './partials/topbar.php' ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">Tasks</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center">Add Task</h3>
                                    <div id="alert"></div>
                                    <form id="add-form">
                                        <div class="row">
                                            <div class="col-md">
                                                <input type="text" class="form-control" name="task-input" id="task-input" placeholder="Please enter the task!">
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="submit" value="Add" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-body">
                                    <h5>Tasks</h5>
                                    <div id="tasks">
                                        <!-- <div class="row mb-2">
                                            <div class="col-md">
                                                <input type="text" class="form-control" id="task-" value="Database Value" placeholder="Please enter the task!" readonly>
                                            </div>
                                            <div class="col-md-auto">
                                                <button class="btn btn-info" id="edit-" onclick="editTask(1)">Edit</button>
                                            </div>
                                            <div class="col-md-auto">
                                                <button class="btn btn-danger" id="delete-" onclick="editTask(1)">Delete</button>
                                            </div>
                                        </div> -->
                                        <!-- <div class="alert alert-info m-0">No record found!</div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php require_once './partials/footer.php' ?>
        </div>
    </div>

    <script src="./assets/js/app.js"></script>
    <script>
        showTasks();

        const addFormElement = document.querySelector("#add-form");
        const alertElement = document.querySelector("#alert");

        addFormElement.addEventListener("submit", async function(e) {
            e.preventDefault();

            const taskInputElement = document.querySelector("#task-input");

            let taskInputValue = taskInputElement.value;

            if (taskInputValue == "") {
                taskInputElement.classList.add("is-invalid");
                alertElement.innerHTML = alert("danger", "Enter the task!");
            } else {
                taskInputElement.classList.remove("is-invalid");
                alertElement.innerHTML = "";

                const data = {
                    body: taskInputValue,
                    submit: 1,
                };

                const response = await fetch("./api/add-task.php", {
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application.json'
                    }
                })
                const result = await response.json();

                if (result.bodyError) {
                    taskInputElement.classList.add("is-invalid");
                    alertElement.innerHTML = alert("danger", result.bodyError);
                } else if (result.success) {
                    alertElement.innerHTML = alert("success", result.success);
                    taskInputElement.value = "";
                    showTasks();
                } else if (result.failure) {
                    alertElement.innerHTML = alert("danger", result.failure);
                } else {
                    alertElement.innerHTML = alert("danger", "Something went wrong!");
                }
            }
        });

        async function showTasks() {
            const tasksElement = document.querySelector("#tasks");

            const response = await fetch("./api/show-tasks.php")
            const result = await response.json();

            let tasksListElement = "";
            if (result.length !== 0) {
                result.forEach(function(task) {
                    tasksListElement += `<div class="row mb-2">
                                            <div class="col-md">
                                                <input type="text" class="form-control" id="task-${task.id}" value="${task.body}" placeholder="Please enter the task!" readonly>
                                            </div>
                                            <div class="col-md-auto">
                                                <button class="btn btn-info" id="edit-${task.id}" onclick="editTask(${task.id})">Edit</button>
                                            </div>
                                            <div class="col-md-auto">
                                                <button class="btn btn-danger" id="delete-${task.id}" onclick="deleteTask(${task.id})">Delete</button>
                                            </div>
                                        </div>`;
                });

                tasksElement.innerHTML = tasksListElement;
            } else {
                tasksElement.innerHTML = `<div class="alert alert-info m-0">No record found!</div>`;
            }
        }

        async function editTask(id) {
            const taskElement = document.querySelector("#task-" + id);
            const editElement = document.querySelector("#edit-" + id);

            let taskValue = taskElement.value;

            if (editElement.innerText == "Edit") {
                taskElement.removeAttribute("readonly");
                taskElement.focus();
                taskElement.setSelectionRange(taskValue.length, taskValue.length);
                editElement.innerText = "Save";
            } else {
                if (taskValue == "") {
                    taskElement.classList.add("is-invalid");
                } else {
                    taskElement.classList.remove("is-invalid");

                    const data = {
                        body: taskValue,
                        id: id,
                        submit: 1,
                    };

                    const response = await fetch("./api/edit-task.php", {
                        method: "POST",
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application.json'
                        },
                    })

                    const result = await response.json()

                    if (result.bodyError) {
                        taskElement.classList.add("is-invalid");
                        alertElement.innerHTML = alert("danger", result.bodyError);
                    } else if (result.success) {
                        alertElement.innerHTML = alert("success", result.success);
                        editElement.innerText = "Edit";
                        taskElement.setAttribute("readonly", true);
                    } else if (result.failure) {
                        alertElement.innerHTML = alert("danger", result.failure);
                    } else {
                        alertElement.innerHTML = alert("danger", "Something went wrong!");
                    }
                }
            }
        }

        async function deleteTask(id) {
            const data = {
                id: id,
                submit: 1,
            };

            const response = await fetch("./api/delete-task.php", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                },
            });

            const result = await response.json();

            if (result.success) {
                alertElement.innerHTML = alert("success", result.success);
                showTasks();
            } else if (result.failure) {
                alertElement.innerHTML = alert("danger", result.failure);
            } else {
                alertElement.innerHTML = alert("danger", "Something went wrong!");
            }
        }

        function alert(cls, msg) {
            return `<div class="alert alert-${cls} alert-dismissible fade show" role="alert">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
    </script>

</body>

</html>