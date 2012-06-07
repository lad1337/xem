<?if(isset($fullelement)):?>
<?if($fullelement->status > 0 || grantAcces(4)):?>
<div id="element" data-id="<?=$fullelement->id?>">
        <h1><?=$fullelement->main_name?></h1>

        <div class="btn-group pull-right">
          <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">Toolbox <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><?=anchor("xem/changelog/".$fullelement->id,"<i class='icon-pencil'></i> Change Log")?></li>
            <?if($editRight || grantAcces(1)):?>

                <?if(!$fullelement->isDraft):?>
                <li><?=anchor("xem/draft/".$fullelement->id,"<i class='icon-hand-right'></i> Draft (".$fullelement->draftChangesCount().") ahead")?></li>
                <?else:?>
                <li><?=anchor("xem/show/".$fullelement->parent,"<i class='icon-hand-left'></i> Public (".$fullelement->draftChangesCount().") behind")?></li>
                    <?if($fullelement->status<4):?>
                    <li><div class="btnWrapper"><input type="button" value="Public Request&hellip;" onclick="requestPublic()" class="btn btn-inverse" /></div></li>
                    <?else:?>
                    <li><div class="btnWrapper"><input type="button" value="Public Request Pending&hellip;" class="btn btn-success" disabled="disabled"/></div></li>
                    <?endif?>
                <?endif?>
                <?if($editRight):?>
                    <li class="divider"></li>
                    <li><?=anchor("#","<i class='icon-retweet'></i> Save Entities Order", array('onclick'=>'saveEntityOrder(); return false;') )?></li>
                    <li><?=anchor("#","<i class='icon-minus-sign'></i> QuickConnect OFF", array('id'=>'toggleQC', 'title'=>'If QuickConnet is ON a direct connection will be made as soon two episodes are marked.', 'onclick'=>'toggleQC(); return false;') )?></li>
                <?endif?>
                <?if(grantAcces(3)):?>
                    <li>
                        <?=form_open("xem/setLockLevel",array('id'=>'setLockLevelForm'))?>
                        <?=form_hidden("element_id",$fullelement->id)?>
                        <div class="btnWrapper"><i class='icon-lock'></i> Lock Level at 
                            <select name="lvl" onchange="this.form.submit();">
                                <?for($i = 1; $i <= 3; $i++):?>
                                <option value="<?=$i?>" <?if($fullelement->status == $i){ echo 'selected="selected"';} ?>><?=$i?></option>
                                <?endfor?>
                            </select>
                        </div>
                        </form>
                    </li>

                <?if(!$fullelement->isDraft):?>
                    <li class="divider"></li>
                    <li>
                        <li><?=anchor("xem/clearCache/".$fullelement->id,"<i class='icon-remove-sign'></i> Clear Cache (" . $fullelement->cacheSize . ")" )?></li>
                    </li>
                <?endif?>
                <?endif?>

                <?if(grantAcces(4)):?>
                <?if($fullelement->status > 0):?>
                <?if(!$fullelement->isDraft):?>
                    <li class="divider"></li>
                    <li><div class="btnWrapper"><input type="button" value="Delete This Show&hellip;" onclick="deleteMe()" class="btn btn-danger" /></div></li>
                <?else:?>
                    <li class="divider"></li>
                    <li><div class="btnWrapper"><input type="button" value="Make Draft Public" data-toggle="modal" href="#confirmMakeDraftPublic" class="btn btn-success" /></div></li>
                    <li><div class="btnWrapper"><input type="button" value="Delete This Draft&hellip;" onclick="deleteMe()" class="btn btn-danger" /></div></li>
                <?endif?>
                <?else:?>
                    <li>
                     <?=form_open("xem/unDeleteShow")?>
                        <?=form_hidden("element_id",$fullelement->id)?>
                        <input type="submit" value="UnDelete This Show" class="btn btn-mini">
                      </form>
                    </li>
                <?endif?>
                <?endif?>
                <li class="divider"></li>
            <?endif?>
          </ul>
        </div>

        <div class="modal fade hide" id="confirmMakeDraftPublic">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>Make Draft Public</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you are ready to submit this for Admin review?</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
                <?=anchor("xem/makePublic/".$fullelement->id,"Submit", array('class'=>'btn btn-primary') )?>
            </div>
        </div>

		<br class="clear"/>
		<div id="alternativeNamesContainer">
    		<?if($fullelement->groupedNames()):?>
    		<div id="alternativeNames">
    			<!--<h2>Alternative Names</h2>-->
    			<table>
    				<?foreach($fullelement->groupedNames() as $season=>$names):?>
    				<tr>
    					<td>
    						<?if($season!=-1):?>
    						<div>Season <?=$season?></div>
    						<?else:?>
    						<div>Alias</div>
    						<?endif?>
    					</td>
    					<td>
    						<div>
    							<ul class="names">
    								<?foreach($names as $curName):?>
    								<li class="name">
                                        <?=img(array('src'=>'images/flags/'.$curName->language.'.png','data-id'=>$curName->id,'data-lang'=>$curName->language,'id'=>'flag_'.$curName->id,'width'=>17))?>
                                        <span class="name" data-id="<?=$curName->id?>"><?=$curName->name?></span>
                                        <div class="clear"></div>
                                    </li>
    								<?endforeach?>
    							</ul>
    						</div>
    					</td>
    				</tr>
    				<?endforeach?>
    			</table>
    		</div>
    		<?endif?>

    		<?if($editRight):?>
    		<div id="newAlternativeName">
    			<?=form_open("xem/newAlternativeName",array('class'=>'form-inline'))?>
    					<?=form_hidden("element_id",$fullelement->id)?>
                        <div class="input-prepend">
                            <span class="add-on">Season</span><input type="text" placeholder="All" id="newNameSeason" name="season" class="input-mini">
                        </div>
                        <select name="language">
                            <?foreach($languages->result() as $curLang):?>
                            <option value="<?=$curLang->id?>" <?if($curLang->id == 'us'){ echo 'selected="selected"';} ?>><?=$curLang->name?></option>
                            <?endforeach?>
                        </select>
    					<input id="newNameName" type="text" name="name" placeholder="Name"/>
    					<input type="submit" value="Add New Name" class="btn" />
    			</form>
    		</div>
            <br/>
    		<?endif?>
		</div>

		<?if($editRight || grantAcces(1)):?>
		<div id="toolbox" style="display: none;">
		  <strong>Toolbox</strong>
		  <ul>
                <?if(!$fullelement->isDraft):?>
                <li><label>Draft (<?=$fullelement->draftChangesCount()?> ahead)</label><input type="button" value="Go To Draft" onclick="window.location = '/xem/draft/<?=$fullelement->id?>'" class="btn btn-mini" /></li>
                <?else:?>
                <li><label>Public (<?=$fullelement->draftChangesCount()?> behind)</label><input type="button" value="Go To Public" onclick="window.location = '/xem/show/<?=$fullelement->parent?>'" class="btn  btn-mini" /></li>
                <?if($fullelement->status<4):?>
                <li><label>Public request</label><input type="button" value="Request&hellip;" onclick="requestPublic()" class="btn btn-mini" /></li>
                <?else:?>
                <li><label>Public request was send&hellip;</label></li>
                <?endif;?>
                <?endif?>
                <?if($editRight):?>
                <li><label>Save entity order</label><input type="button" value="Save" onclick="saveEntityOrder()" class="btn btn-mini" /></li>
                <li><label title="If QuickConnet is ON a direct connection will be made as soon two episodes are marked.">QuickConnet</label><input type="button" value="OFF" onclick="if(quickConnet){quickConnet = false; $(this).val('OFF')}else{quickConnet = true; $(this).val('ON')}" class="btn btn-mini" /></li>
                <?endif?>
                <?if(grantAcces(3)):?>
                <li>
                    <?=form_open("xem/setLockLevel",array('id'=>'deleteShowForm'))?>
                    <?=form_hidden("element_id",$fullelement->id)?>

                    <label>Lock Level</label><select name="lvl">
                        <?for($i = 1; $i <= 3; $i++):?>
                        <option value="<?=$i?>" <?if($fullelement->status == $i){ echo 'selected="selected"';} ?>><?=$i?></option>
                        <?endfor?>
                        </select>
                        <input type="submit" value="Set" class="btn btn-mini"/>
                    </form>
                </li>

                <?if(!$fullelement->isDraft):?>
                <li>
                    <?=form_open("xem/clearCache",array('id'=>'deleteShowForm'))?>
                        <?=form_hidden("element_id",$fullelement->id)?>
                        <label>Clear cache (<?=$fullelement->cacheSize?>)</label><input type="submit" value="Clear" class="btn btn-mini" />
                    </form>
                </li>
                <?endif?>
                <?endif?>

              <?if(grantAcces(4)):?>
              <?if($fullelement->status > 0):?>
                <?if(!$fullelement->isDraft):?>
                <li><label>Delete</label><input type="button" onclick="deleteMe()" value="Delete This Show&hellip;" class="btn btn-danger btn-mini" /></li>
                <?else:?>
                <li><label>Make draft public</label><input type="button" onclick="window.location = '/xem/makePublic/<?=$fullelement->id?>'" value="Make Public" class="btn btn-success btn-mini"/></li>
                <li><label>Delete</label><input type="button" onclick="deleteMe()" value="Delete This Draft&hellip;" class="btn btn-danger btn-mini"/></li>
                <?endif?>
              <?else:?>
              <li>
                 <?=form_open("xem/unDeleteShow")?>
                    <?=form_hidden("element_id",$fullelement->id)?>
                    <input type="submit" value="UnDelete This Show" class="btn btn-mini">
                  </form>
              </li>
              <?endif?>
              <?endif?>

              <?if(!$fullelement->isDraft):?>
    		  <li><label>This <strong>show</strong> has a lvl of <strong><?=$fullelement->status?></strong></label></li>
              <?else:?>
              <li><label>This <strong>draft</strong> has a lvl of <strong><?=$fullelement->status?></strong></label></li>
              <?endif?>
              <li><label><?=anchor('xem/changelog/'.$fullelement->id,'Changelog')?></label></li>
		  </ul>
		</div>
		<?endif;?>
        <br class="clear-keep-height"/>
		<!-- this is a fix for the windows firefox svg 1px top offset problem. a div,p, h3(wtf?) or any other block stuff didnt work -->
		<!-- ok is not a fix .... this can create and remove the effect -->
		<!--<h2 style="height: 45px;">&nbsp;</h2>-->
		<div class="clear-keep-height">
			<ul id="sortable">
				<?foreach($fullelement->sortedEntitys() as $curLocation):$absolute_number = 1;?>
				<? include('editShow_singleEntity.php');?>
				<?endforeach?>
				<li class="clear"></li>
			</ul>
			<div class="clear"></div>
		</div>
</div>
<!-- show script and functions -->
<script type="text/javascript">
var logedIn = <?=json_encode($logedIn)?>;
var editRight = <?=json_encode($editRight)?>;
var abstractConObjs = <?=$fullelement->getJSONDirectrules()?>;
var passthruConObjs = <?=$fullelement->getJSONPassthrus()?>;
var languages = <?=$languagesJS?>;
</script>
<script src="/js/shows.js"></script>
<?else:?>
<h2><?=$fullelement->main_name?></h2>
<p>This show was deleted!!</p>
<p><?=anchor('xem/changelog/'.$fullelement->id,'Changelog')?></p>

<?endif?>
<?endif?>
