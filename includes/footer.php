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
				$.extend(true, $.fn.dataTable.defaults, {
					language: {
						search: "Rechercher :",
						lengthMenu: "Afficher _MENU_ éléments",
						info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
						infoEmpty: "Affichage de 0 à 0 sur 0 élément",
						infoFiltered: "(filtré de _MAX_ éléments au total)",
						zeroRecords: "Aucun résultat trouvé",
						emptyTable: "Aucune donnée disponible",
						loadingRecords: "Chargement...",
						processing: "Traitement...",
						paginate: {
							first: "Premier",
							last: "Dernier",
							next: "Suivant",
							previous: "Précédent"
						}
					}
				});

				var storageKey = 'simpleErpSidebarCollapsed';
			var themeStorageKey = 'simpleErpTheme';
			var $body = $('body');
			var $toggle = $('#sidebarToggle');
			var $overlay = $('#appOverlay');
			var $themeToggle = $('#themeToggle');
			var $themeIcon = $('#themeToggleIcon');

			function applyTheme(theme) {
				document.documentElement.setAttribute('data-theme', theme);

				if (!$themeToggle.length) {
					return;
				}

				var isDark = theme === 'dark';
					$themeToggle.attr('aria-label', isDark ? 'Passer en mode clair' : 'Passer en mode sombre');
					$themeToggle.attr('title', isDark ? 'Passer en mode clair' : 'Passer en mode sombre');
				$themeIcon.attr('class', isDark ? 'fa fa-sun-o' : 'fa fa-moon-o');
			}

			function readTheme() {
				try {
					return window.localStorage.getItem(themeStorageKey) === 'dark' ? 'dark' : 'light';
				} catch (error) {
					return 'light';
				}
			}

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

			$themeToggle.on('click', function() {
				var nextTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
				$body.addClass('theme-transition');
				applyTheme(nextTheme);

				try {
					window.localStorage.setItem(themeStorageKey, nextTheme);
				} catch (error) {
				}

				window.setTimeout(function() {
					$body.removeClass('theme-transition');
				}, 250);
			});

			$(window).on('resize', syncDesktopState);

			applyTheme(readTheme());
			syncDesktopState();
		})(jQuery);
	</script>

</body>
</html>
