@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .content {
        position: absolute;
        height: calc(100% - 46px);
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Users</h3>
        </div>
        <div class="container-fluid m-0 p-2 content">
            <div class="container-fluid border p-0" style="height:100%;overflow-y:auto;">
                <div class="container-fluid m-0 p-1">
                    <button class="btn btn-dark" style="letter-spacing: 2px;" id="addNewUserButton">
                        ADD NEW USER
                    </button>
                </div>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr class="table-dark" style="position: sticky;top:0;">
                            <th scope="col" class="border">User ID</th>
                            <th scope="col" class="border">Name</th>
                            <th scope="col" class="border">Username</th>
                            <th scope="col" class="border">Type</th>
                            <th scope="col" class="border"></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="letter-spacing: 2px;">ADD NEW USER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid my-2">
                    <label for="nameInput">Name</label>
                    <input type="text" class="nameInput form-control border-secondary text-center">
                </div>
                <div class="container-fluid my-2">
                    <label for="usernameInput">Username</label>
                    <input type="text" class="usernameInput form-control border-secondary text-center">
                </div>
                <div class="container-fluid my-2">
                    <label for="passwordInput">Password</label>
                    <div class="container-fluid m-0 p-0" style="display:flex;flex-direction:row;gap:5px;">
                        <input type="password" class="passwordInput form-control border-secondary text-center">
                        <button class="btn btn-dark showPassButton">Show</button>
                    </div>
                </div>
                <div class="container-fluid my-2">
                    <label for="typeSelect">User Type</label>
                    <select class="typeSelect form-control border-secondary text-center">
                        <option value="">---Select---</option>
                        <option value="1">ADMIN</option>
                        <option value="0">CASHIER</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" id="addUserButton">Add User</button>
            </div>
        </div>
    </div>
</div>

<!--Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="letter-spacing: 2px;">EDIT USER INFORMATION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid my-2">
                    <label for="userIdInput">User ID</label>
                    <input type="text" class="userIdInput form-control border-secondary text-center" disabled>
                </div>
                <div class="container-fluid my-2">
                    <label for="nameInput">Name</label>
                    <input type="text" class="nameInput form-control border-secondary text-center">
                </div>
                <div class="container-fluid my-2">
                    <label for="usernameInput">Username</label>
                    <input type="text" class="usernameInput form-control border-secondary text-center">
                </div>
                <div class="container-fluid my-2">
                    <label for="typeSelect">User Type</label>
                    <select class="typeSelect form-control border-secondary text-center">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" id="updateButton">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!--Change Password Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="letter-spacing: 2px;">CHANGE USER PASSWORD</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid my-2">
                    <label for="userIdInput">User ID</label>
                    <input type="text" class="userIdInput form-control border-secondary text-center" disabled>
                </div>
                <div class="container-fluid my-2">
                    <label for="newPassInput">New Password</label>
                    <div class="container-fluid m-0 p-0" style="display:flex;flex-direction:row;gap:5px;">
                        <input type="password" id="newPassInput" class="form-control border-secondary text-center">
                        <button class="btn btn-dark showPassButton">Show</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" id="changePasswordButton">Change Password</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        const tableBody = $("#table-body");
        const addUserModal = $("#addUserModal");
        const editUserModal = $("#editUserModal");
        const changePassModal = $("#changePassModal");

        const getUsers = () => {
            $.ajax({
                type: "get",
                url: "{{ route('get-users.admin') }}",
                success: (data) => {
                    if (data.length > 0) {
                        data.forEach((row) => {
                            const userData = {
                                user_id: row.id,
                                user_name: row.name,
                                user_username: row.username,
                                user_type: row.type,
                            }

                            userRow(userData);
                        });
                    } else {
                        tableBody.append("<tr><td colspan='5'>No users...</tr>");
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        const userRow = (userData) => {
            const {
                user_id,
                user_name,
                user_username,
                user_type,
            } = userData;

            var tableRow = $("<tr>");
            var userId = `<th class='border'>${user_id}</th>`;
            var name = `<td class='border'>${user_name}</td>`;
            var username = `<td class='border'>${user_username}</td>`;
            var type = `<td class='border'>${user_type}</td>`;
            var buttonsColumn = $("<td class='border'>");
            var buttons = `<button id='editUserButton' data-user-id='${user_id}'>Edit User</button> \
                            <button id='changePassButton' data-user-id='${user_id}'>Change Password</button> \
                            <button id='removeUserButton' data-user-id='${user_id}'>Remove User</button>`;

            tableBody.append(tableRow);
            tableRow.append(userId);
            tableRow.append(name);
            tableRow.append(username);
            tableRow.append(type);
            tableRow.append(buttonsColumn);
            buttonsColumn.append(buttons);
        };

        const getUserInfo = (userId) => {
            $.ajax({
                type: "get",
                url: "{{ route('get-user-info.admin') }}",
                data: {
                    user_id: userId,
                },
                success: (data) => {
                    displayUserInfoInEditUserModal(data);
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        const displayUserInfoInEditUserModal = (data) => {
            var typeNumber;
            var typeValue = "";
            var typeOptionNumber;
            var typeOptionValue = "";

            editUserModal.find(".userIdInput").val(data.id);
            editUserModal.find(".nameInput").val(data.name);
            editUserModal.find(".usernameInput").val(data.username);

            if (data.type === "admin") {
                typeNumber = 1;
                typeValue = "ADMIN";
                typeOptionNumber = 0;
                typeOptionValue = "CASHIER";
            } else {
                typeNumber = 0;
                typeValue = "CASHIER";
                typeOptionNumber = 1;
                typeOptionValue = "ADMIN";
            }

            editUserModal.find(".typeSelect").empty();
            editUserModal.find(".typeSelect").append(`<option value='${typeNumber}'>${typeValue}</option>`);
            editUserModal.find(".typeSelect").append(`<option value='${typeOptionNumber}'>${typeOptionValue}</option>`);
        };


        const addNewUserInfo = (info) => {
            const {
                name,
                username,
                password,
                type
            } = info;

            $.ajax({
                type: "post",
                url: "{{ route('add-user-info.admin') }}",
                data: {
                    name: name,
                    username: username,
                    password: password,
                    type: type,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: (response) => {
                    if (response) {
                        alert("New user successfully created.");
                        window.location.reload();
                    } else {
                        alert("Failed to create user info.");
                    }
                },
                error: (xhr, status, error) => {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;

                        console.log(errors);
                    } else {
                        console.error(xhr.responseText);
                    }
                }
            });
        };

        const updateUserInfo = (info) => {
            const {
                id,
                name,
                username,
                type
            } = info;

            $.ajax({
                type: "put",
                url: "{{ route('update-user-info.admin') }}",
                data: {
                    id: id,
                    name: name,
                    username: username,
                    type: type,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: (response) => {
                    if (response) {
                        alert(`User ID [${id}] information successfully updated.`);
                        window.location.reload();
                    } else {
                        alert("Failed to update user info.");
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        const changeUserPassword = (info) => {
            const {
                id,
                newPass
            } = info;

            $.ajax({
                type: "put",
                url: "{{ route('update-user-password.admin') }}",
                data: {
                    id: id,
                    new_pass: newPass,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: (response) => {
                    if (response) {
                        alert("Password successfully changed.");
                        window.location.reload();
                    } else {
                        alert("Failed to change user password.");
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        const deleteUser = (userId) => {
            $.ajax({
                type: "delete",
                url: "{{ route('delete-user.admin') }}",
                data: {
                    id: userId,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: (response) => {
                    if (response) {
                        alert("User is successfully deleted.");
                        window.location.reload();
                    } else {
                        alert("Failed to delete user.");
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        addUserModal.on('click', '.showPassButton', function() {
            const passInput = addUserModal.find(".passwordInput");

            if (passInput.attr('type') === 'password') {
                passInput.attr('type', 'text');
                $(this).text("Hide")
            } else {
                passInput.attr('type', 'password');
                $(this).text("Show")
            }
        });

        changePassModal.on('click', '.showPassButton', function() {
            const passInput = changePassModal.find("#newPassInput");

            if (passInput.attr('type') === 'password') {
                passInput.attr('type', 'text');
                $(this).text("Hide")
            } else {
                passInput.attr('type', 'password');
                $(this).text("Show")
            }
        });

        $(document).on('click', '#addNewUserButton', function() {
            addUserModal.modal("show");
        });

        $(document).on('click', '#editUserButton', function() {
            var userId = $(this).data('user-id');

            getUserInfo(userId);
            editUserModal.modal("show");
        });

        $(document).on('click', '#changePassButton', function() {
            var userId = $(this).data('user-id');

            changePassModal.find(".userIdInput").val(userId);
            changePassModal.modal("show");
        });

        $(document).on('click', '#removeUserButton', function() {
            var userId = $(this).data('user-id');

            if (confirm("Do you want to remove this user permanently?")) {
                deleteUser(userId);
            }
        });

        addUserModal.on('click', '#addUserButton', () => {
            var name = addUserModal.find(".nameInput").val();
            var username = addUserModal.find(".usernameInput").val();
            var password = addUserModal.find(".passwordInput").val();
            var type = addUserModal.find(".typeSelect").val();

            const info = {
                name: name,
                username: username,
                password: password,
                type: type
            }

            if (name !== "" && username !== "" && password !== "" && type !== "") {
                if (confirm("Do you want to create this user?")) {
                    addNewUserInfo(info);
                }
            } else {
                alert("Please fill all the inputs");
            }

        });

        editUserModal.on('click', '#updateButton', () => {
            var userId = editUserModal.find(".userIdInput").val();
            var name = editUserModal.find(".nameInput").val();
            var username = editUserModal.find(".usernameInput").val();
            var type = editUserModal.find(".typeSelect").val();

            const info = {
                id: userId,
                name: name,
                username: username,
                type: type
            }

            if (userId !== "" && name !== "" && username !== "" && type !== "") {
                if (confirm("Do you want to update this user information?")) {
                    updateUserInfo(info);
                }
            } else {
                alert("Please fill all the inputs");
            }
        });

        changePassModal.on('click', '#changePasswordButton', () => {
            var userId = changePassModal.find(".userIdInput").val();
            var newPass = changePassModal.find("#newPassInput").val();

            const info = {
                id: userId,
                newPass: newPass,
            }

            if (newPass !== "") {
                if (confirm("Do you want to change this user password?")) {
                    changeUserPassword(info);
                }
            } else {
                alert("Please input new password");
            }
        });

        getUsers();
    });
</script>
@endsection