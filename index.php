<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Load CSV</title>
        <!-- Bootstrap CSS -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <link rel="stylesheet/less" type="text/css" href="style/style.less" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js"></script>
        <style>
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="page-header">
            <div class="container">
                <h1>CSVDump <small>Dump CSV files into a MySQL Database <em>fast</em></small></h1>
            </div>
        </div>
<?php
// Just check that our Config file exists and display a message otherwise
if(!file_exists('./CSVRunner/Config.php')):
?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="warning" id="config_file_not_exists">
                        <p>You are missing the /CSVRunner/Config.php file.</p>
                    <?php if(file_exists('./CSVRunner/Config.example.php')): ?>
                        <p>Edit <code>/CSVRunner/Config.example.php</code> with your database details and save as <code>/CSVRunner/Config.php</code>.</p>
                    <?php else: ?>
                        <p>Also missing <code>/CSVRunner/Config.example.php</code>, please <a href='https://github.com/manticorp/CSVDump'>re-download the repository.</a></p>";
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
// Else display the interface
else: // if(!file_exists('./CSVRunner/Config.php')):
            include "./CSVRunner/CSVRunner.php";
            $baseurl = explode('?', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])[0];
            $msg   = null;
            $error = false;
            if (isset($_GET['delete']) && isset($_GET['fn'])) {
                if (file_exists($_GET['fn'])) {
                    unlink($_GET['fn']);
                    $msg  = "File $_GET[fn] deleted";
                } else {
                    $msg   = "File $_GET[fn] not found";
                    $error = true;
                }
            }
            $processors = CSVRunner::getProcessors();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>Input</h2>
                    <label for="inputDb">Database
                        <input type="text" name="db"    id="inputDb"    class="form-control" value=""    title="" placeholder="e.g. test">
                    </label>
                    <label for="inputTable">Table
                        <input type="text" name="table" id="inputTable" class="form-control" value="" title="" placeholder="e.g. testTable">
                    </label>
                    <label for="processor">Processor Class
                        <select name="processor" id="processor" class="form-control" required="required">
                            <option value="">No Processing</option>
                            <?php foreach ($processors as $p) printf("<option value=\"%s\">%s</option>\n", $p, $p); ?>
                        </select>
                    </label>
                    <button class="btn btn-primary btn-small" type="button" data-toggle="collapse" data-target="#advancedOptions" aria-expanded="false" aria-controls="advancedOptions">
                    Advanced Options <span class="glyphicon glyphicon glyphicon-cog"></span>
                    </button>
                    <div class="collapse" id="advancedOptions">
                        <div class="well">
                            <label for="inputChunks">Chunk Size
                                <input type="number" name="chunks" id="inputChunks" class="form-control" value="100" min="5" max="10000" step="1" title="Processing step size">
                            </label>
                            <label for="delimiter">Field Delimiter
                                <select name="delimiter" id="delimiter" class="form-control" required="required">
                                    <option value="">Auto Detect</option>
                                    <?php foreach (CSVRunner::$delimiters as $p) printf("<option value=\"%s\">%s</option>\n", $p, str_replace("\t","\\t",$p)); ?>
                                </select>
                            </label>
                            <label for="quotechar">Quote Char
                                <select name="quotechar" id="quotechar" class="form-control" required="required">
                                    <option value="">Auto Detect</option>
                                    <?php foreach (CSVRunner::$quoteChars as $p) printf("<option value=\"%s\">%s</option>\n", $p, str_replace("\t","\\t",$p)); ?>
                                </select>
                            </label>
                            <label for="escapechar">Escape Char
                                <select name="escapechar" id="escapechar" class="form-control" required="required">
                                    <option value="">Auto Detect</option>
                                    <?php foreach (CSVRunner::$escapeChars as $p) printf("<option value=\"%s\">%s</option>\n", $p, str_replace("\t","\\t",$p)); ?>
                                </select>
                            </label>
                            <div class="checkbox">
                                <label for="inputReplace">
                                    <input type="checkbox" checked value="" name="hh" id="inputReplace" title="Whether to replace existing data">
                                    Replace Data
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" checked value="" name="hh" id="inputHh" title="Whether your csv has headers">
                                    Has Headers
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php if ($msg !== null): ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-<?php echo ($error) ? 'danger' : 'success';?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong><?php echo ($error) ? '<i class="glyphicon glyphicon-warning-sign"></i> Warning' : 'Success';?></strong> <?php echo $msg;?>
                    </div>
                </div>
            </div>
        <?php endif; //($msg !== null) ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive" id="file-table">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Filename</th>
                                    <th>Size</th>
                                    <th>Rows (est.)</th>
                                    <th>Dump</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; foreach (glob(APPLICATION_PATH . '/input/*.csv') as $fn): $i++; ?>
                                <tr class="main-row">
                                    <td class="expand"><span class="glyphicon glyphicon-chevron-down"></span></td>
                                    <td><?php echo basename($fn);?></td>
                                    <td><?php echo CSVRunner::fileSizeString($fn, 1);?></td>
                                    <td><?php echo CSVRunner::numRowsInFile($fn);?></td>
                                    <td class="dump-file"><a href='#script-progress' class='process-file' data data-jp='false' data-fn='<?php echo urlencode(realpath($fn));?>'><i class="glyphicon glyphicon-import"></i> Dump to <span class="databaseName"><?php echo $db['db'] . '</span>.<span class="tableName">' . CSVRunner::getDBName($fn);?></span></a></td>
                                    <td class="delete-file" data-toggle="tooltip" data-placement="top" title="Warning: This will delete the file permanently, it will not be recoverable">
                                        <a href='http://<?=$baseurl;?>?delete=true&fn=<?php echo urlencode(realpath($fn));?>'>Delete File <i class="glyphicon glyphicon-trash"></i> <i class="glyphicon glyphicon-warning-sign"></i></a>
                                    </td>
                                </tr>
                                <tr class="secondary-row">
                                    <td colspan=6>
                                        <div class="slider">
                                            <table class="table table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Column</th>
                                                        <th>Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $cols = CSVRunner::getFirstRow($fn);
                                                foreach($cols as $col):
                                                ?>
                                                    <tr>
                                                        <td><?php echo $col; ?></td>
                                                        <td>
                                                            <select data-col="<?php echo $col;?>" name="colType">
                                                                <option value="auto">Auto Detect</option>
                                                                <?php foreach(CSVRunner::$colTypes as $type): ?>
                                                                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>This feature has not been implemented yet.</td>
                                                    </tr>
                                                <? endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>Script Output Display</h2>
                    <div class="progress hidden" id="script-progress">
                        <div class="progress-bar progress-bar-striped active" id="progress-bar-start" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                    <div id="script-output">Nothing to output<span id="typed-cursor" class="blinking">|</span></div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="//code.jquery.com/jquery.js"></script>
        <script src='js/main.js' type="text/javascript"></script>
<?php endif; // if(!file_exists('./CSVRunner/Config.php')): ?>
        <!-- Bootstrap JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </body>
</html>