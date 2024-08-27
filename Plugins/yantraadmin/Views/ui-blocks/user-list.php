<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Users</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Users</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-one">
                            <input type="text" id="usernameSearch" placeholder="Search by Username">
                            <div class="table-container"></div>
                            <div class="pagination-container"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<?php
 add_action('footer-scripts',function (){
     ?>
     <script type="text/javascript">

         const config = {
             perPage: 10, // Number of records per page
             orderColumns: { 'ID': 'ASC' }
         };

         const table = new yantra.JsonTable(
             '#table-one',
             app.url('admin/user-list'),
             [
                 { name: 'ID', label: 'ID', sortable: true, order:'ASC', render:function (value,rec){
                     let url = app.url('admin/users/user?id='+value);
                     return `<a href="${url}">${value}</a>`;}},
                 { name: 'email', label: 'Email', sortable: true },
                 { name: 'first_name', label: 'First Name', sortable: true },
                 { name: 'username', label: 'Username', sortable: true, val:{ username: () => document.querySelector('#usernameSearch').value } }
             ],
             config
         )

         document.querySelector('#usernameSearch').addEventListener('input', () => {
             table.refresh();
         });
     </script>

<?php
 });
?>