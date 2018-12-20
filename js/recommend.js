$(document).ready(function () {
	var $modal = $("#tile_recommend_modal");

	$(".tile_recommend").each(function (i, button) {

		var $button = $(button);
		var $form;
		var $submit;

		$button.click(click);

		/**
		 * @returns {boolean}
		 */
		function click() {
			var get_url = $button.attr("href");

			$.get(get_url, show);

			return false;
		}

		/**
		 * @param {string} html
		 */
		function show(html) {
			$modal.find(".modal-body").html(html);

			$form = $("#form_tile_recommend_modal_form");
			$submit = $("#tile_recommend_modal_submit");
			var $cancel = $("#tile_recommend_modal_cancel");

			$form.submit(submit);
			$cancel.click(cancel);

			$modal.modal("show");
		}

		/**
		 * @returns {boolean}
		 */
		function submit() {
			var post_url = $form.attr("action");

			var data = new FormData($form[0]);
			data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

			$.ajax({
				type: "post",
				url: post_url,
				contentType: false,
				processData: false,
				data: data,
				success: show
			});

			return false;
		}

		/**
		 * @returns {boolean}
		 */
		function cancel() {
			$modal.modal("hide");

			return false;
		}
	});
});
