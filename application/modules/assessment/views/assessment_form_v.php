<?php $sess_login = $this->session->userdata('login_session'); ?>

<section class="content-header">
	<h3 class="box-title">Form Penilaian | <?= $jobTitleName->name ?></h3>
	<ol class="breadcrumb">
		<h3 class="box-title pull-right">Waktu pengisian form penilaian skill 2 - 15 Mei 2019</h3>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
	<!-- /.box-header -->
		<div class="box-body">
			<form action="" method="">
				<a class="btn btn-primary mr-2" href="<?= base_url('export_to_excel/'.$jobTitleName->id) ?>">
	                <i class="fa fa-print"></i> Print Form
	            </a>

	            <!-- {{-- submit button just show if user is participant --}} -->
	            <?php if($sess_login['group'] == 3 && $sess_login['level'] == 2) : ?>
	                <!-- {{-- check whether form has submited or no --}} -->
	                <?php if ($isSubmited > 0) : ?>
	                    <button type="button" class="btn btn-success pull-right" onclick="alert('Form has submitted!')">
	                        <i class="fa fa-check"></i> Form has submitted
	                    </button>
	                <?php else : ?>
	                    <?php if ($statementAmount == 0 && $completeAssessment == 0) : ?>
	                        <button type="button" class="btn btn-warning pull-right" onclick="alert('There\'s no competency for this job title!')">
	                            <i class="fa fa-check"></i> Submit Form
	                        </button>
	                    <?php elseif ($statementAmount != $completeAssessment) : ?>
	                        <button type="button" class="btn btn-warning pull-right" onclick="alert('Please complete the form before submit!')">
	                            <i class="fa fa-check"></i> Submit Form
	                        </button>
	                    <?php else : ?>
                            <a 
                            	href="<?= base_url('submit_form/'.$jobTitleName->id) ?>" 
                            	class="btn btn-warning pull-right" 
                            	onclick="return confirm('Dengan ini anda menyatakan bahwa penilaian yang dilakukan adalah benar. Anda Yakin?')">
                                <i class="fa fa-check"></i> Submit Form
                            </a>
	                    <?php endif; ?>
	                <?php endif; ?>
	            <?php endif; ?>
	            <hr>
				<table class="table table-hover table-bordered">
					<thead>
                        <tr>
                            <th style="white-space:nowrap;" rowspan="2">NIK</th>
                            <th style="white-space:nowrap;" rowspan="2">Nama Pegawai</th>

                            <?php foreach ($dictionary->result() as $dictlist) : ?>
                                <th style="text-align:center;" colspan="2">
                                    <?= $dictlist->name_id  ?>
                                </th>
                            <?php endforeach; ?>

                            <th style="white-space:nowrap;" rowspan="2">Nilai Absolut</th>
                        </tr>
                        <tr>

                            <?php for ($i = 0; $i < count($dictionary->result()); $i++) : ?>
                                <!-- edit poin just show if user is assessment participant -->
                                <?php if($sess_login['group'] == 3 && $sess_login['level'] == 2) : ?>
                                    <th style="text-align:center">Isi Nilai</th>
                                <!-- see detail poin show if user admin/PA -->
                                <?php elseif ($sess_login['group'] == 1 || $sess_login['group'] == 2) : ?>
                                    <th style="text-align:center">Detail Nilai</th>
                                <?php endif; ?>
                                <th style="text-align:center">Nilai</th>
                            <?php endfor; ?>

                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($employes->result() as $employe) : ?>
                            <tr>
                                <!-- red flag for uncomplete employe assessment -->
                                <?php 
                                    $isFullfilled = is_value_complete(count($dictionary->result()),$employe->nik,$active_year);
                                    if (!$isFullfilled) {
                                        $columnColor = 'background: #fabacf;';
                                        $info = 'data-toggle="tooltip" data-placement="left" data-original-title="Penilaian untuk karyawan ini belum terisi penuh!"';
                                    } else {
                                        $columnColor = '';
                                        $info = '';
                                    }
                                 ?>

                                <td style="white-space:nowrap; <?= $columnColor ?>" <?=$info?>>
                                    <?= $employe->nik; ?>
                                </td>
                                <td style="white-space:nowrap; <?= $columnColor ?>" <?=$info?>>
                                    <?= $employe->name; ?>
                                </td>

                                <?php foreach ($dictionary->result() as $dicts) : ?>
                                <!-- {{-- button edit poin just show if user is assessment participant --}} -->
                                <?php if ($sess_login['group'] == 3 && $sess_login['level'] == 2) : ?>
                                <!-- {{-- cannot edit poin when form has submitted --}} -->
                                <td style="text-align:center">

                                    <?php if ($isSubmited > 0) : ?>
                                    <button class='btn btn-sm btn-default' type="button" title='input nilai'>
                                        <i class='fa fa-edit'></i>
                                    </button>

                                    <?php else : ?>
                                    <button class='btn btn-sm btn-default' type="button" title='input nilai' data-toggle='modal' data-target='#addModal' onclick="loadCompetency('<?= $dicts->skill_id ?>','<?= $employe->nik ?>','<?= $employe->job_title_id ?>')">
                                        <i class='fa fa-pencil'></i>
                                    </button>
                                    <?php endif; ?>

                                </td>
                                <!-- {{-- if user login is admin/PA, show detail poin --}} -->
                                <?php elseif ($sess_login['group'] == 1 || $sess_login['group'] == 2) : ?>
                                <td style="text-align:center">
                                    <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#detailPoin" onclick="loadDetailPoin('<?= $dicts->skill_id ?>','<?=$employe->nik ?>')" title="Lihat detail nilai">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>

                                <?php endif; ?>

                                    <?php
                                        // get assessment form to get its ID
                                        $assessmentForm = $this->db->where('nik', $employe->nik)
                                        							->like('code',$active_year,'before')
                                        							->get('assessment_forms');

                                        // its ID will use to get detail form question
                                        $formId = $assessmentForm->row()->id;

                                        $detailPoint = $this->db->query("SELECT * from assessment_form_questions ass 
                                        								JOIN skill_units un ON ass.skill_unit_id = un.id 
                                        								where un.id_dictionary = '".$dicts->skill_id."' 
                                        								AND poin IS NOT NULL 
                                        								AND ass.form_id = '".$formId."'");

                                        // count competency unit which have NOT NULL poin (for average point)
                                        $filledAssessment = count($detailPoint->result());

                                        // count amount of each unit competency
                                        $const = 0;
                                        foreach ($detailPoint->result() as $value) {
                                            $const = $const + ($value->weight * $value->poin);
                                        }

                                        // average point of each competency dictionary
                                        $averagePoint = $const;
                                    ?>

                                <td style="text-align:center">
                                    <input type="text" style='width:4em' min='1' class="pointof-<?= $formId ?>" value="<?= $averagePoint ?>" max='100' readonly>
                                </td>
                                <?php endforeach; ?>

                                <input type="hidden" name="jobid" value="<?php $employe->job_title_id ?>">
                                <td style="text-align:center">
                                    <?php if ($assessmentForm->num_rows() < 1) : ?>
                                        <input type='text' name='absolutepoint' style='width:4em' min='1' value="0" max='100' readonly>
                                    <?php elseif (is_null($assessmentForm->row()->total_poin)) : ?>
                                        <input type='text' name='absolutepoint' id="<?= $employe->nik ?>" style='width:4em' min='1' value="" max='100' readonly>
                                        <!-- {{-- js for count amount of poin of each employee --}} -->
                                        <script>
                                            let inputBox_<?= $formId ?> = document.getElementsByClassName('pointof-<?=$formId?>');
                                            let constanta_<?= $formId ?> = 0;
                                            for (let i = 0; i < inputBox_<?= $formId ?>.length; i++) {
                                                constanta_<?= $formId ?> += parseFloat(inputBox_<?= $formId ?>[i].value);
                                            }
                                            document.getElementById('<?= $employe->nik ?>').value = constanta_<?= $formId ?>;
                                        </script>
                                    <?php else : ?>
                                        <input type='text' name='absolutepoint' style='width:4em' min='1' value="<?= $assessmentForm->row()->total_poin ?>" max='100' readonly>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
				</table>
				<a href="<?= base_url('assessment') ?>" class="btn btn-primary pull-right"><i class="fa fa-chevron-left"></i> Back</a>
			</form>
		</div>
	</div>
</section>

<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="form-id" method="POST" action="<?= base_url('store_poin') ?>" onsubmit="return checkform(this);">
            <!-- Modal content-->
            <div class="modal-content" id="field-poin">

            </div>
        </form>
    </div>
</div>

<div id="infomodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title-mod">Assessment form information</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Rentang nilai untuk pengisian <i>assessment form</i> adalah 1 - 5.</p>
                <p>
                    Dimana:
                    <ul>
                        <li>1 = Sangat tidak baik</li>
                        <li>2 = Tidak baik</li>
                        <li>3 = Cukup</li>
                        <li>4 = Baik</li>
                        <li>5 = Sangat baik</li>
                    </ul>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="detailPoin" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="detailpoin-content">

        </div>
    </div>
</div>

<script>
    var pathArray = window.location.pathname.split('/');

    function loadCompetency(skill_id,nik,job_id) {
        $('#field-poin').load(location.origin + '/' + pathArray[1] + '/nik/'+nik+'/jobtitle/'+job_id+'/competency/'+skill_id+'/assessment');
    }

    function loadDetailPoin(skill_id,nik) {
        $('#detailpoin-content').load(location.origin + '/' + pathArray[1] +'/assessment/'+skill_id+'/competency/'+nik+'/nik');
    }
</script>
