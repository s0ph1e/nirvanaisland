/* jqEasy drop down sign in form
 * Examples and documentation at: http://www.jqeasy.com/
 * Version: 1.0 (22/03/2010)
 * No license. Use it however you want. Just keep this notice included.
 * Requires: jQuery v1.3+
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */
$(document).ready(function() {
	$('.btnsignin').click(function(e) {
		e.preventDefault();
		$("#frmsignin").toggle('fast',function() {
				$('#identity').focus();
			});
		$(this).toggleClass("btnsigninon");
		$('#msg').empty();
	});
	
	$('.btnsignin').mouseup(function() {
		return false;
	});
	
	$(document).mouseup(function(e) {
		if($(e.target).parents('#frmsignin').length==0) {
			$('.btnsignin').removeClass('btnsigninon');
			$('#frmsignin').hide('fast');
		};
	});
	
	
	
	$('#addCommentForm').ajaxForm({
		success: function(data) {
			data = eval('(' + data + ')');	
			if (data.response==1) {			
				$(data.html).hide().insertBefore('#addCommentContainer').slideDown(); 
				$('#textarea_comment').val('');	
			} 
		}
	});
	
	$('#signin').ajaxForm({
		beforeSubmit: validate,
		success: function(data) {
			data = eval('(' + data + ')');
			if (data.response=='OK') {
				$('#frmsignin').text('Вы успешно вошли!');
				$('#frmsignin').delay(800).fadeOut(400);
				$('#signbtn').html('<a href="' + site_url + '/auth/logout" class="btnsignout" id="btnsignout">Выйти</a>');
			} else {
				$('#msg').html(data.additional);
				$('#identity').focus();
			}
		}
	});
	
	$('.cart_ok').click(function(e) {
		e.preventDefault();
		$.ajax({
			url: site_url + "/shopcart/update/" + this.id + '/' + $('#'+this.id).val()
		}).done(function(data) { 
			data = eval('(' + data + ')');
			if($('#' + data.id).val()==0)
			{
				$('#total_' + data.id).parent().parent().fadeOut();
			}
			else 
			{
				$('#total_' + data.id).html(data.total_price);
			}
			$('#cart_message').html("В корзине " + data.all_qty + " товаров на сумму " + data.all_price + " грн.");
		});
		
		return false;
	});
	
	$('.cart_del').click(function(e) {
		e.preventDefault();
		$.ajax({
			url: site_url + "/shopcart/delete/" + this.id
		}).done(function(data) { 
			data = eval('(' + data + ')');
			$('#total_' + data.id).parent().parent().fadeOut();
			$('#cart_message').html("В корзине " + data.all_qty + " товаров на сумму " + data.all_price + " грн.");
		});
		
		return false;
	});
	
	$('.status_select').change(function(e) {
		e.preventDefault();
		$.ajax({
			url: site_url + "/admin/change_status/" + this.id + "/" + $('#'+this.id).val()
		}).done(function(data) { 
			data = eval('(' + data + ')');
			if(view_id!=3&&view_id != data.status)
			{
				$('#' + data.id).parent().parent().fadeOut();
			}
		});
		
		return false;
	});
	
	$('.cat_add').click(function(e) {
		e.preventDefault();
		$("#form_add_cat").slideDown()
	});
	
	$('.cat_add').mouseup(function() {
		return false;
	});
	
	$(document).mouseup(function(e) {
		if($(e.target).parents('#form_add_cat').length==0) {
			$('#form_add_cat').slideUp();
		};
	});	

	$('.cat_del').click(function(e) {
		e.preventDefault();
		if (confirm("При удалении категории будут также удалены все подкатегории и товары.\nВы подтверждаете удаление?"))
		{
			$.ajax({
				url: site_url + "/admin/cat_del/" + this.id.split('_')[1]
			}).done(function(data) { 
				data = eval('(' + data + ')');
				$('#del_' + data.id).parent().parent().fadeOut();
			});
		}
		return false;
	});
	
	$('.cat_edit').click(function(e) {
		e.preventDefault();
		var id = this.id.split('_')[1];
		var category = $('#cat_name_' + id);
		category.html('<input type="text" class="newname" name="newname" id="new_cat_name_' + id + '" value="' + category.find('a').html() + '"><a href="#" class="confirm_cat_edit" id="' + id + '"><img src="' + base_url + '/data/images/ok.png' + '"></a>');
		return false;
	});
	
	$('.confirm_cat_edit').live('click', function(e) {
		e.preventDefault();
		var id = this.id;

		$.ajax({
			type: 'POST',
			url: site_url + "/admin/cat_edit/" + id,
			data: { newname: $('#new_cat_name_' + id).val()}
		}).done(function(data) {
			data = eval('(' + data + ')');
			$('#cat_name_' + data.id).html(data.link);
		});
		
		return false;
	});
	
	$('.prod_del').click(function(e) {
		e.preventDefault();
		$.ajax({
			url: site_url + "/admin/prod_del/" + this.id.split('_')[1]
		}).done(function(data) { 
			data = eval('(' + data + ')');
			$('#prod-del_' + data.id).parent().parent().fadeOut();
		});
		return false;
	});
});

function validate(formData, jqForm, options) { 
	var form = jqForm[0];
	var un = $.trim(form.identity.value);
	var pw = $.trim(form.password.value);
	var unReg = /^[A-Za-z0-9_@\.]{5,100}$/;
	var pwReg = /^[A-Za-z0-9!@#$%&*()_]{6,20}$/;
	var hasError = false;
	var errmsg = '';
	
	if (!un) { 
		errmsg = '<p>Введите e-mail</p>';
		hasError = true;
	} else if(!unReg.test(un)) {
		errmsg = '<p>E-mail должен состоять из симоволов (a-z, 0-9, _, @, ".").</p>';
		hasError = true;
	}
	
	if (!pw) { 
		errmsg += '<p>Введите пароль</p>';
		hasError = true;
	} else if(!pwReg.test(pw)) {
		errmsg += '<p>Пароль должен состоять из симоволов (a-z, 0-9, !, @, #, $, %, &, *, (, ), _).</p>';
		hasError = true;
	}
	
	if (!hasError) {
		$('#msg').html('<p><img src="' + base_url + '/data/images/loading.gif" alt="loading" /> подождите...</p>');
	} else {
		$('#msg').html(errmsg);
	return false;
	}
}