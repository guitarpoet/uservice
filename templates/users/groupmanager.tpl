{include file="header.tpl"}
			<script type="text/javascript">
				var root = '{$smarty.const.ROOT}/index.php';
				initialize = function($) {
					$('.datagrid tbody tr input').each(function(){
						if($(this).attr('checked')){
							$(this).parents('tr').addClass('checked');
						}
					});
					$('.datagrid tbody tr').mouseover(function(){
						$(this).addClass('active');
					}).mouseout(function(){
						$(this).removeClass('active');
					}).click(function(event){
						if($(event.target).attr('type') != 'checkbox')
							$(this).find('input').attr('checked', !$(this).find('input').attr('checked'));
						$(this).toggleClass('checked');
					});
					function clearChecks() {
						$('.datagrid tbody tr').removeClass('checked').find('input[type=checkbox]').attr('checked', null);
						$('#check_all_user').attr('checked', null);
					}
					$('#check_all_user').click(function(){
						$('.datagrid tbody tr').click();
					});
					$( "#edit_dialog" ).dialog({
						autoOpen: false,
						show: "blind",
						hide: "explode",
						width: 'auto',
						buttons: {
							submit: function(){
								$.post(root, {
									operation: 'groupmanager',
									type: $('#edit_type').val(),
									id: $('#group_id').val(),
									groupname: $('#groupname').val(),
									description: $('#description').val(),
									format: 'json'
								}, function(){
									$('#edit_dialog').dialog('close');
									showMessage("{show text='Group are updated'}","{show text='Success'}", true);
								});
							}
						}
					});

					function checkIfNoGroupSelected() {
						if($('.datagrid tr.checked').length == 0) {
							showMessage("{show text='No group selected!'}", "{show text='Error'}");
							return true;
						}
					}

					$('#modify').click(function(){
						if($('.datagrid tr.checked').length > 1) {
							showMessage("{show text='More than one group selected!'}", "{show text='Error'}");
							return;
						}
						if(checkIfNoGroupSelected())
							return;
						var group = $('.datagrid tr.checked td');
						$('#group_id').val(group.eq(1).text());
						$('#groupname').val(group.eq(2).text());
						$('#description').val(group.eq(3).text());
						$('#edit_type').val('update');
						$('#edit_dialog').dialog('option', 'title', "{show text='Edit Group - '}" + group.eq(2).text()).dialog('open');
					});
					function getGroupIDs() {
						var groupIds = [];
						$('.datagrid tr.checked').each(function(){
							groupIds.push($(this).children('td').eq(1).text().trim());
						});
						return groupIds;
					}

					$('#add').click(function(){
						$('#edit_type').val('insert');
						$('#edit_dialog').dialog('option', 'title', "{show text='Create group'}").dialog('open');
					});
					$('#delete').click(function(){
						if(checkIfNoGroupSelected())
							return;
						$.post(root, {
							operation: 'groupmanager',
							type: 'delete',
							format: 'json',
							ids: getGroupIDs().join(',')
						}, function(){
							showMessage("{show text='Groups are deleted'}","{show text='Success'}", true);
							clearChecks();
						});
					});
					$('#suspend').click(function(){
						if(checkIfNoGroupSelected())
							return;
						$.post(root, {
							operation: 'usermanager',
							type: 'suspend',
							format: 'json',
							ids: getUserIDs().join(',')
						}, function(){
							showMessage("{show text='Users are suspended'}","{show text='Success'}", true);
							clearChecks();
						});
					});
					$('#page').change(function(){
						$('#query_form').submit();
					});
				}
			</script>
			<div id="groups_result">
				<form id="query_form" action="{$smarty.const.ROOT}/index.php">
					<input type="hidden" name="operation" value="usermanager"/>
					<div id="groups_control" class="toolbar">
						<input type='button' id="add" class="button" value="{show text='Add'}"/>
						<input type='button' id="modify" class="button" value="{show text='Modify'}"/>
						<input type='button' id="delete" class="button" value="{show text='Delete'}"/>
					</div>
					<table class="datagrid" cellspadding='1'>
						<thead>
							<tr>
								<th>
									<input type="checkbox" id="check_all_user" />
								</th>
								<th>{show text='ID'}</th>
								<th>{show text='Group Name'}</th>
								<th>{show text='Description'}</th>
								<th>{show text='Creation Time'}</th>
								<th>{show text='Last Modification Time'}</th>
								<th>{show text='Creator'}</th>
							</tr>
						</thead>
						<tbody>
							{section name=group loop=$groups}
							<tr>
								<td>
									<input type="checkbox" name="{$groups[group].id}" />
								</td>
								<td><a href='{get_link target="{$groups[group].id}" type="group"}'>{$groups[group].id}</a></td>
								<td><a href='{get_link target="{$groups[group].id}" type="group"}'>{$groups[group].groupname}</a></td>
								<td>{$groups[group].description}</td>
								<td>{$groups[group].creation_time}</td>
								<td>{$groups[group].last_modification_time}</td>
								<td>{$groups[group].username}</td>
							</tr>
							{/section}
						</tbody>
						<tfoot>
							<tr height='5'>
								<td colspan='10'>
									{show text='Goto Page'}
									<select id="page" name="page">
										{section name=i loop=$page_count}
											<option value='{$i}'>{$i+1}</option>
										{/section}
									</select>
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
			<div id="edit_dialog" title="{show text='Edit Group'}">
				<form action="{$smarty.const.ROOT}/index.php" class="form container">
					<dl>
						<dt>
							<label for="groupname">{show text='Group Name'}:</label>
						</dt>
						<dd>
							<input id="groupname" name="groupname" class="text_input" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="description">{show text='description'}:</label>
						</dt>
						<dd>
							<textarea id="description" name="description" class="text_input" ></textarea>
						</dd>
					</dl>
					<input type="hidden" id="edit_type" name="type"/>
					<input type="hidden" id="group_id" name="id"/>
				</form>
			</div>
{include file="footer.tpl"}
