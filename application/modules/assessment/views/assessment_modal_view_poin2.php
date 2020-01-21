<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title" id="title-mod"><?= $dictionary->name_id ?> | <?= $employname->name ?></h4>
    <h5><?= $dictionary->description ?></h5>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <h4>Nilai Mentah</h4>
                <p>
                    1 = Sangat tidak baik,&nbsp;&nbsp;&nbsp;  
                    2 = Tidak baik,&nbsp;&nbsp;&nbsp;  
                    3 = Cukup,&nbsp;&nbsp;&nbsp;  
                    4 = Baik,&nbsp;&nbsp;&nbsp;  
                    5 = Sangat baik
                </p>
            </div>
        </div>
    </div>
    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="white-space:nowrap;">Pernyataan</th>
                        <th style="white-space:nowrap;">Nilai Mentah</th>
                        <th style="white-space:nowrap;">Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($competency as $items) : ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $items->description ?></td>
                        <td><input type="text" style="width:4em"  value="<?= $items->poin ?>" disabled=""></td>
                        <td><input style="width:4em" type="text" value="<?= $items->weight ?>" disabled=""></td>
                    </tr>
                    <?php $no++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>