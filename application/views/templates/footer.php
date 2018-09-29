</div> <!-- body-content -->
<?php
  if (strpos(current_url(), 'admin/') != FALSE) {
    echo '</div>'; // container-fluid
  }
?>

<footer>
  <ul>
    <div class="copyright-text"><span id="date"></span> &copy; ekultura.hu</div>
    <li><a href="<?php echo site_url('calendar'); ?>">Napi évfordulók</a></li>
    <?php foreach ($statics as $st): ?>
      <li><a href="<?php echo site_url($st['path']); ?>">
        <?php echo $st['title']; ?>
      </a></li>
    <?php endforeach; ?>
    <?php if($logged_in): ?>
      <li><a href="<?php echo site_url(array('users', 'user_settings')); ?>">Beállítások</a></li>
    <?php else: ?>
      <li><a data-toggle="modal" data-target="#loginModal" role="button">Belépés</a></li>
    <?php endif; ?>
  </ul>
</footer>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" aria-labelledby="">Bejelentkezés</h4>
			</div>
			<div class="modal-body">
				<?php $size = 50;
					$attributes = array('class' => 'form-horizontal');
					$hiddens = array('current_url' => current_url());
					echo form_open('users/login', $attributes, $hiddens); ?>
					<div class = "form-group">
						<label for = "username" class="col-md-3 control-label">Felhasználónév</label>
						<div class="col-md-8">
							<?php $data = array(
										'name'        => 'username',
										'value'       => set_value('username'),
										'maxlength'   => '128',
										'size'        => $size,
										'class'		  => 'form-control',
										'placeholder' => 'Felhasználónév'
										);
								echo form_input($data); ?>
						</div>
					</div>
				
					<div class = "form-group">
						<label for = "password" class="col-md-3 control-label">Jelszó</label>
						<div class="col-md-8">
							<?php $data = array(
										'name'        => 'password',
										'value'       => '',
										'maxlength'   => '128',
										'size'        => $size,
										'class'		  => 'form-control',
										'placeholder' => 'Jelszó'
										);
								echo form_password($data); ?>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-3 col-md-8">
							<button type="submit" name="save" value="save" class="btn btn-default">Belépés</button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
			</div>
		</div>
	</div>
</div>

<script>
	document.getElementById('date').innerHTML = new Date().getFullYear();
  $('#search-field').autocomplete({
    delay: 1000,
    minLength: 3,
    source: (req, res) => {
      $.getJSON(`<?php echo site_url(array('articles', 'get_articles_by_search_short')); ?>/${$('#search-field').val()}`, data => {
        res(data);
      })
    },
  }).autocomplete('instance')._renderItem = function(ul, item) {
    return $(`<li class="${item.subcat_slug}">`)
      .append(`<a href="${item.link}">${item.title}</a>`)
      .appendTo(ul);
  };

	$('.menu-buttons').click(() => {
		$('#nav-icon').toggleClass('open');
		const inCloseState = $('#menu-text').html() === 'Bezár';
    $('#menu-text').html(inCloseState ? 'Menü' : 'Bezár');
		$('.header .navbar-links').toggleClass('show-menu');
		$('#content-mask').toggleClass('content-hidden');
		$('.header').toggleClass('header-fixed');
		['.search-container', '.body-content', 'footer.navbar'].forEach(selector => {
			$(selector).toggleClass('content-fixed');
		});
		if (!inCloseState) {
			$('#content-mask').one('click', () => {
				$('.menu-buttons').click();
			});
		}
	});
</script>
</body>
</html>