<template>
  <div class="hello">
    <img alt="Vue logo" height="64" width="64" src="@/assets/logo.png">
    <h3>{{ msg }}</h3>
  </div>
</template>

<script>
  export default {
    name: 'HelloWorld',
    props: {
      msg: String
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  h3 {
    margin: 40px 0;
  }
</style>
