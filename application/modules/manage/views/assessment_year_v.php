<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?= base_url('assets') ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<section class="content-header">
    <h3 class="box-title">Assessment Year <small>Set your assessment year configuration here</small></h3>
  
    <!-- flash data for fail data save -->
    <?php if ($this->session->flashdata('fail_remove_data')) { ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> <?= $this->session->flashdata('fail_remove_data') ?></h5>
      </div>
    <?php } ?>

    <!-- flash data for fail data save -->
    <?php if ($this->session->flashdata('fail_save_data')) { ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> <?= $this->session->flashdata('fail_save_data') ?></h5>
      </div>
    <?php } ?>

    <!-- flash data for successfully data save -->
    <?php if ($this->session->flashdata('success_save_data')) { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-check"></i> <?= $this->session->flashdata('success_save_data') ?></h5>
      </div>
    <?php } ?>

    <!-- flash data for successfully data update -->
    <?php if ($this->session->flashdata('success_update_data')) { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-refresh"></i> <?= $this->session->flashdata('success_update_data') ?></h5>
      </div>
    <?php } ?>

    <!-- flash data for successfully data remove -->
    <?php if ($this->session->flashdata('success_remove_data')) { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-trash"></i> <?= $this->session->flashdata('success_remove_data') ?></h5>
      </div>
    <?php } ?>

    <!-- flash data for successfully data remove -->
    <?php if ($this->session->flashdata('success_change_active_year')) { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-check"></i> <?= $this->session->flashdata('success_change_active_year') ?></h5>
      </div>
    <?php } ?>
</section>

<!-- Main content -->
<section class="content">
  
  <div class="box">
    <div class="box-body">
      <div class="table-wrap">
        <div class="table-responsive">
          <button 
              data-toggle="modal" 
              data-target="#addModal" 
              class="btn btn-info">
              <i class="fa fa-plus"></i> Add Year
          </button>
          <hr>
          <table class="table table-hover table-bordered example1">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Year</th>
                  <th>Status</th>
                  <th>Set Periode</th>
                  <th width="140">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($years as $year) : ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $year->year ?></td>
                  <td>
                    <?= $year->is_active == '1' ? '<span class="label bg-green">Active</span>' : '<span class="label bg-red">Inactive</span>' ?>
                  </td>
                  <td>
                    <button 
                      class="btn btn-default"
                      data-toggle="modal"
                      data-target="#periodModal"
                      onclick="set_period(<?= $year->id ?>)">
                      <i class="fa fa-calendar"></i>
                    </button>
                  </td>
                  <td>
                      <button
                        data-toggle="modal"
                        data-target="#editModal"
                        onclick="edit(<?= $year->id ?>)"
                        class="btn btn-default"><i class="fa fa-edit"></i></button>
                      <a
                        href="<?= base_url('assessment_year/'.$year->id.'/set_active') ?>"
                        class="btn btn-default"><i class="fa fa-refresh"></i></a>
                      <a
                        onclick="return confirm('Are You sure want to remove this data?')"
                        href="<?= base_url('assessment_year/'.$year->id.'/remove') ?>"
                        class="btn btn-default"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php $no++; endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
</section>

<div id="addModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Assessment Year</h4>
      </div>
      <form action="<?= base_url('assessment_year/store') ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="year">Year</label>
              <input 
                type="text" 
                class="form-control" 
                id="year"
                name="year" 
                onkeypress="return isNumber(event)" 
                maxlength="4" 
                required="">
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="isActive" value="1">
                  Is active?
                </label>
              </div> 
            </div>
          </div>
          <div class="modal-footer">
              <button type="submit" id="btnSubmit" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Assessment Year</h4>
        </div>
        <form action="<?= base_url('assessment_year/store') ?>" method="post">
            <div class="modal-body">
              <input type="hidden" name="isUpdate" id="isUpdate" value="">
              <div class="form-group">
                <label for="yearEdit">Year</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="yearEdit"
                  name="year" 
                  onkeypress="return isNumber(event)" 
                  maxlength="4" 
                  required="">
              </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSubmit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>

<div id="periodModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Set Assessment Year Period</h4>
        </div>
        <form action="<?= base_url('assessment_year/set_period') ?>" method="post">
            <div class="modal-body">
              <input type="hidden" name="yearId" id="yearId" value="">
              <div class="form-group">
                <label for="">Year</label>
                <input 
                  type="text" 
                  class="form-control"
                  id="year_name" 
                  name="yearName"  
                  value=""
                  readonly="">
              </div>
              <div class="form-group">
                <label for="">Date Start</label>
                <input 
                  type="text" 
                  class="form-control date"
                  name="startDate"  
                  id="startDate"  
                  required="">
              </div>
              <div class="form-group">
                <label for="">Date End</label>
                <input 
                  type="text" 
                  class="form-control date"
                  name="endDate"  
                  id="endDate"  
                  required="">
              </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSubmit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- bootstrap datepicker -->
<script src="<?= base_url('assets') ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }

  function edit(id) {
    $.get('<?= base_url() ?>assessment_year/' + id + '/edit', function(response) {
      var res = JSON.parse(response);
      $('#yearEdit').val(res.name);
      $('#isUpdate').val(res.id);
    })
  }

  $(function() {
    //Date picker
    $('.date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    })
  })

  function set_period(id) {
    $.get('<?= base_url() ?>assessment_year/' + id + '/edit', function(response) {
      var res = JSON.parse(response);
      $('#year_name').val(res.name);
      $('#yearId').val(res.id);
      $.get('<?= base_url() ?>assessment_year/' + res.id + '/period', function(res) {
        var resp = JSON.parse(res);
        $('#startDate').val(resp.start_date);
        $('#endDate').val(resp.end_date);
      })
    })
  }
</script>