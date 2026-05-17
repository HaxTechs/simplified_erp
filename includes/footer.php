			</main>
		</div>
	</div>
	

	<!-- file input -->
	<script src="assests/plugins/fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>	
	<script src="assests/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>	
	<script src="assests/plugins/fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
	<script src="assests/plugins/fileinput/js/fileinput.min.js"></script>	


	<!-- DataTables -->
	<script src="assests/plugins/datatables/jquery.dataTables.min.js"></script>
	<script>
		(function($) {
			var storageKey = 'simpleErpSidebarCollapsed';
			var $body = $('body');
			var $toggle = $('#sidebarToggle');
			var $overlay = $('#appOverlay');

			function persistCollapsedState(collapsed) {
				try {
					window.localStorage.setItem(storageKey, collapsed ? '1' : '0');
				} catch (error) {
				}
			}

			function readCollapsedState() {
				try {
					return window.localStorage.getItem(storageKey) === '1';
				} catch (error) {
					return false;
				}
			}

			function syncDesktopState() {
				if (window.innerWidth > 991) {
					$body.toggleClass('sidebar-collapsed', readCollapsedState());
					$body.removeClass('sidebar-open');
				} else {
					$body.removeClass('sidebar-collapsed');
				}
			}

			$toggle.on('click', function() {
				if (window.innerWidth <= 991) {
					$body.toggleClass('sidebar-open');
					return;
				}

				var shouldCollapse = !$body.hasClass('sidebar-collapsed');
				$body.toggleClass('sidebar-collapsed', shouldCollapse);
				persistCollapsedState(shouldCollapse);
			});

			$overlay.on('click', function() {
				$body.removeClass('sidebar-open');
			});

			$(window).on('resize', syncDesktopState);

			syncDesktopState();
		})(jQuery);
	</script>

</body>
</html>
