<form id="frmCSVImport" name="uploadCSV" method="post" action="" enctype="multipart/form-data">
    <div class="demo-table">
        <div class="form-head">%title%</div>
        <div class="%message%">
            <p>%response%</p>
            <div id="response">
                %list_error%
                %list_response%
            </div>
        </div>
        <div class="field-column">

            <label class="col-md-4 control-label">Choose CSV File</label>
            <div>
                <input class="demo-input-box" type="file" name="file" id="file" accept=".csv">
            </div>

            <div class="field-column">
                <input type="submit" name="import" value="Import" class="btnImport">
            </div>
        </div>
    </div>
</form>