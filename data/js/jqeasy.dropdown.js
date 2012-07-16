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
			data = eval('(' + data + ')');	// Преобразование JSON
			if (data.response==1) {			// Если нет ошибок
				$(data.html).hide().insertBefore('#addCommentContainer').slideDown(); // Плавное появление комментария
				$('#textarea_comment').val('');		// Очистка textarea
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
			if(data.total_price == "0 грн.")
			{
				$('#total_' + data.id).parent().parent().hide();
			}
			else 
			{
				$('#total_' + data.id).html(data.total_price);
			}
			$('#cart_message').html("В корзине " + data.all_qty + " товаров на сумму " + data.all_price + " грн.")
		});
		
		return false;
	});
	
	$('.cart_del').click(function(e) {
		e.preventDefault();
		$.ajax({
			url: site_url + "/shopcart/delete/" + this.id
		}).done(function() { 
			alert('dddd');
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