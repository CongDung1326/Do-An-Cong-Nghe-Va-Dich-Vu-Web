<!-- Content Wrapper START -->
<div class="main-content">
    <div class="container-fluid">
        <!-- Breadcrumb Start -->
        <div class="breadcrumb-wrapper row">
            <div class="col-12 col-lg-3 col-md-6">
                <h4 class="page-title">Coupons</h4>
            </div>
            <div class="col-12 col-lg-9 col-md-6">
                <ol class="breadcrumb float-right">
                    <li><a href="index.html">Home</a></li>
                    <li class="active">/ Coupons</li>
                </ol>
            </div>
        </div>
        <!-- Breadcrumb End -->
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header border-bottom flex d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Coupons</h4>
                <button type="button" class="btn btn-common waves-effect waves-light" data-toggle="modal" data-target="#modalCoupon">Add Coupon</button>
                <!-- Modal Coupon -->
                <div id="modalCoupon" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Add Coupon Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="" class="col-form-label">Test:</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-common waves-effect waves-light">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                            <th>Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011-04-25</td>
                            <td>$320,800</td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm" style="margin-right: 5px;">Edit</button>
                                <button class="my-button btn btn-danger btn-sm">Delete</button>
                                <!-- Alert -->
                                <div class="my-alert hide">
                                    <span class="lni-warning"></span>
                                    <span class="msg">Warning: Tét 1!</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Donna Snider</td>
                            <td>Customer Support</td>
                            <td>New York</td>
                            <td>27</td>
                            <td>2011-01-25</td>
                            <td>$112,000</td>
                            <td class="d-flex">
                                <button class="btn btn-primary btn-sm" style="margin-right: 5px;">Edit</button>
                                <button class="my-button btn btn-danger btn-sm">Delete</button>
                                <!-- Alert -->
                                <div class="my-alert hide">
                                    <span class="lni-warning"></span>
                                    <span class="msg">Warning: Tét 2!</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let myTable = $("table#example");
        myTable.DataTable({
            stateSave: true,
            info: false,
        });

        $(".my-button").each((index, value) => {
            $(value).click((e) => {
                $(value).next().addClass("show");
                $(value).next().removeClass("hide");
                $(value).next().addClass("showAlert");
                setTimeout(function() {
                    $(value).next().removeClass("show");
                    $(value).next().addClass("hide");
                }, 2500);
            })
        })
    })
</script>
<!-- Content Wrapper END -->