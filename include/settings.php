<?
ikutkan("head.php");
ikutkan("menu.php");

$path = "./config.json";
if(isset($_GET['save'])){
	if(count($_POST)>2){
		$json = json_encode($_POST);
		if(file_put_contents($path,$json)){
			?><div class="alert alert-success">
				<button class="close" data-dismiss="alert">×</button>
				Saving data <b>success!</b>
				</div><?
		}else{
			?><div class="alert alert-error">
			<button class="close" data-dismiss="alert">×</button>
			 <h4 class="alert-heading">Error!</h4>
				Saving data <b>failed!</b>
			</div><?	
		}
	}
}

?>

<form role="form" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded" action="?apa=<?=$apa?>&save">
    <fieldset>
    <legend align="center">Pengaturan</legend>
		<?php
            $conf = json_decode(file_get_contents($path),true);
            foreach ( $conf as $key => $value ) {
				if($value=='true' || $value=='false'){
					?>
					<div class="control-group">
						<label class="control-label" for="<?=$key?>"><b><?=str_replace("_"," ",$key)?></b></label>
                        <select id="<?=$key?>" name="<?=$key?>" style="width: 100%;" class="form-control">
                        	<option value="true" <? if($value=='true') echo 'selected';?>>Ya</option>
                        	<option value="false" <? if($value=='false') echo 'selected';?>>Tidak</option>
                        </select>
					</div>
					<? 

				}else if($value=='separator'){
					?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><hr><?
				}else{
					?>
					<div class="control-group">
						<label class="control-label" for="<?=$key?>"><b><?=str_replace("_"," ",$key)?></b></label>
						<input type="text" style="width: 100%;" class="form-control" id="<?=$key?>" name="<?=$key?>" value="<?= $value?>">
					</div>
					<? 
				}
			} ?>
        <br>
        <button type="submit" class="btn" style="width: 100%;" >Simpan</button>
    </fieldset>
</form>
<br><br>
