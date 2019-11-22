<?php
script('joplin', 'script');
style('joplin', 'style');
?>

<div id="app">

	<?php if (false): ?>
		<div id="app-navigation">
			<?php print_unescaped($this->inc('navigation/index')); ?>
			<?php print_unescaped($this->inc('settings/index')); ?>
		</div>
	<?php endif; ?>

	<div id="app-content">
		<div id="app-content-wrapper">
			<?php
				//print_unescaped($this->inc('content/index'));
			?>

			<?php
				//p('Page: ' . $_['pageName']);
				print_unescaped($this->inc('content/note'));
			?>
		</div>
	</div>
</div>

