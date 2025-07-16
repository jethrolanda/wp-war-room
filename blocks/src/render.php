<?php

/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

add_filter('show_admin_bar', '__return_false');
wp_enqueue_script('drawer-js');

global $wpwr;

wp_interactivity_state(
	'warroom-block',
	array(
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('datefilter-nonce')
	),
);

// $wpwr->callrail->callrail_test();
// $wpwr->hubspot->wpdocs_footag_func();
$context = array(
	"showSkyColor" => false,
	"showGrassColor" => false,
	"attributes" => $attributes,
	"hubspot" => count($wpwr->hubspot->get_hubspot_data()),
	"callrailKickAss" => count($wpwr->callrail->get_callrail_data(array('tag' => 'A-FLKickAss'))),
	"callrailNeedsFuel" => count($wpwr->callrail->get_callrail_data(array('tag' => 'A-NeedsFuel'))),
	// Compare the last two weeks
	"hubspotCompare" => count($wpwr->hubspot->get_hubspot_data(
		array(
			'dateStart' => date('Y-m-d', strtotime('-2 week')),
			'dateEnd' => date('Y-m-d', strtotime('-1 week'))
		)
	)),
	"callrailKickAssCompare" => count($wpwr->callrail->get_callrail_data(
		array(
			'tag' => 'A-FLKickAss',
			'dateStart' => date('Y-m-d', strtotime('-2 week')),
			'dateEnd' => date('Y-m-d', strtotime('-1 week'))
		)
	)),
	"callrailNeedsFuelCompare" => count($wpwr->callrail->get_callrail_data(
		array(
			'tag' => 'A-NeedsFuel',
			'dateStart' => date('Y-m-d', strtotime('-2 week')),
			'dateEnd' => date('Y-m-d', strtotime('-1 week'))
		),
	)),
);

?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="warroom-block"
	<?php echo wp_interactivity_data_wp_context($context); ?>>
	<div id="notification" style="display: none;" class="flex mb-2 p-2 text-base text-[#fff] rounded-xs border border-[#274916] bg-green-600">
		<p id="processing" style="display: none;">Processing your request. Please wait...</p>
		<p id="success" style="display: none;">Process done!</p>
	</div>
	<div class="flex flex-row justify-between items-center">
		<h1 class="!font-extrabold"><span class="text-[#676867]">XAMMIS</span><span class="text-[#e4664a]">WAR</span><span class="text-[#72b843]">ROOM</span></h1>
		<div>
			<label for="options" class="block mb-2 text-sm font-bold">EASY Select Date Range</label>
			<div id="antd-drawer"></div>
			<form id="date-filter" data-wp-on--submit="actions.submitForm" style="display: none;">
				<div id="date-range">
					<input type="text" name="date-start" id="date-start">
					<input type="text" name="date-end" id="date-end">
				</div>
				<div id="date-compare">
					<input type="text" name="compare-start" id="compare-start">
					<input type="text" name="compare-end" id="compare-end">
				</div>
			</form>

			<!-- <select
				id="date"
				name="date"
				class="block w-full px-4 py-2 font-bold text-lg bg-[#bbbcba]">
				<option value="">YESTERDAY / 7 Days / Month / Quarter / Custom</option>
				<option value="1">Option One</option>
				<option value="2">Option Two</option>
				<option value="3">Option Three</option>
			</select> -->
			<p class="absolute text-sm font-bold">Comparison date select: YOY, MOM, WOW</p>
		</div>
		<div>
			<label for="options" class="block mb-2 text-sm font-bold">CAMPAIGN OR ACCOUNT VIEW</label>
			<select
				id="campaign"
				name="campaign"
				class="block w-full px-4 py-2 font-bold text-lg text-[#73b844] bg-[#beda9d]">
				<option value="">SELECT ADS CAMPAIGN</option>
				<option value="1">Option One</option>
				<option value="2">Option Two</option>
				<option value="3">Option Three</option>
			</select>
		</div>
	</div>


	<div class="flex gap-2 flex-col p-4 bg-[#72b843] text-white mt-12">
		<div class="flex flex-row items-center gap-10">
			<h1 class="!font-extrabold">WIDGETS</h1>
			<p class="!font-bold text-[#a8d084]">This is just app data displayed for me</p>
		</div>

		<div class="grid grid-cols-6 grid-rows-1 gap-4 text-[#73b844] font-bold text-center">
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div>$</div>
					<div class="text-[#e02f23]">133k</div>
				</div>
				<div class="text-5xl py-4">120k</div>
				<div class="text-sm">ADS COST</div>
			</div>
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div></div>
					<div class="text-[#e02f23]">-33</div>
				</div>
				<div class="text-5xl py-4">566</div>
				<div class="text-sm">TOTAL CONVERSIONS</div>
			</div>
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div>$</div>
					<div class="text-[#009746]">-21</div>
				</div>
				<div class="text-5xl py-4">343</div>
				<div class="text-sm">COST PER CONVERSION</div>
			</div>
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div></div>
					<div class="text-[#009746]" data-wp-class--is-red="state.callrailKickAssComputedColor" data-wp-text="state.callrailKickAssComputed">0</div>
				</div>
				<div class="text-5xl py-4" data-wp-text="context.callrailKickAss"></div>
				<div class="text-sm">TOTAL KICK ASS</div>
			</div>
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div></div>
					<div class="text-[#009746]" data-wp-class--is-red="state.callrailNeedsFuelComputedColor" data-wp-text="state.callrailNeedsFuelComputed">0</div>
				</div>
				<div class="text-5xl py-4" data-wp-text="context.callrailNeedsFuel"></div>
				<div class="text-sm">TOTAL NEEDS FUEL</div>
			</div>
			<div class="bg-[#a8d084] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div></div>
					<div class="text-[#009746]" data-wp-class--is-red="state.hubspotCompareComputedColor" data-wp-text="state.hubspotComputed">0</div>
				</div>
				<div class="text-5xl py-4" data-wp-text="context.hubspot"></div>
				<div class="text-sm">TOTAL ADS FORM</div>
			</div>
		</div>
	</div>

	<div class="flex gap-2 flex-col p-4 bg-[#e4664a] text-white mt-12">
		<div class="flex flex-row justify-between items-center">
			<h1 class="!font-extrabold">KA COST</h1>
			<div>
				<select
					id="options"
					name="options"
					class="block w-full px-4 py-2 font-bold text-lg text-[#e4674b] bg-[#eea88a]">
					<option value="">MORE FILTERS - TBA</option>
					<option value="1">Option One</option>
					<option value="2">Option Two</option>
					<option value="3">Option Three</option>
				</select>
			</div>
		</div>

		<div class="grid grid-cols-6 grid-rows-1 gap-4 text-[#e4674b] font-bold text-center">
			<div class="bg-[#eea88a] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div>$</div>
					<div class="text-[#009746]">-$44</div>
				</div>
				<div class="text-5xl py-4">350</div>
				<div class="text-sm">TOTAL CPKA</div>
			</div>
			<div class="bg-[#eea88a] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div>$</div>
					<div>DALLAS</div>
					<div class="text-[#009746]">-$96</div>
				</div>
				<div class="text-5xl py-4">410</div>
				<div class="text-sm text-[#009746]">WINNER</div>
			</div>
			<div class="bg-[#eea88a] py-2">
				<div class="flex justify-between mx-2 text-sm">
					<div>$</div>
					<div>NEW YORK</div>
					<div class="text-[#e02f23]">+$321</div>
				</div>
				<div class="text-5xl py-4">967</div>
				<div class="text-sm">LOSER</div>
			</div>
			<div class="flex items-center justify-center bg-[#eea88a] py-2">TBA</div>
			<div class="flex items-center justify-center bg-[#eea88a] py-2">TBA</div>
			<div class="flex items-center justify-center bg-[#eea88a] py-2">TBA</div>
		</div>
	</div>
	<br>
	<div>
		MAP HERE
	</div>
</div>