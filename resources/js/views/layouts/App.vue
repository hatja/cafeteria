<template>

	<div class="main">
		<router-view></router-view>
	</div>

</template>

<script>

	export default {
		components: {  },
	};
</script>

<style lang="css">
	/* GLOBAL STYLES */
	body {
		font-family: system-ui;
	}
	a,
	a:hover {
		text-decoration: none;
	}

    .brand {
        font-size: 24pt;
        color: rgb(255, 255, 255);
    }
	ul {
		list-style: none;
	}
	/* CUSTOM STYLES */
	header {
		background: rgb(0, 0, 0);
	}

	.main {
		display: flex;
		height: 500px;
		align-items: center;
	}
</style>
