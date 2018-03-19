$(document).ready(function () {
	$('#menu-button').materialMenu('init', {
		position: 'overlay',
		items: [
			{
				type: 'normal',
				text: 'Example item 1'
			},
			{
				type: 'normal',
				text: 'Example item 2'
			},
			{
				type: 'normal',
				text: 'Two-line menu item example 1'
			},
			{
				type: 'normal',
				text: 'Two-line menu item example 2'
			},
			{
				type: 'divider'
			},
			{
				type: 'submenu',
				text: 'Example sub-menu',
				items: []
			},
			{
				type: 'divider'
			},
			{
				type: 'toggle',
				text: 'Toggle item 1',
				checked: true
			},
			{
				type: 'toggle',
				text: 'Toggle item 2'
			},
			{
				type: 'divider'
			},
			{
				type: 'radio',
				text: 'Radio item 1',
				radioGroup: 'radio',
				checked: true
			},
			{
				type: 'radio',
				text: 'Radio item 2',
				radioGroup: 'radio'
			},
			{
				type: 'radio',
				text: 'Radio item 3',
				radioGroup: 'radio'
			}
		]
	}).click(function () {
		$(this).materialMenu('open');
	});
});