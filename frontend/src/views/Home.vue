<template>
  <default-layout>
    <div class="home">
      <body></body>
    </div>
  </default-layout>

</template>

<script>
// @ is an alias to /src
import DefaultLayout from "@/components/DefaultLayout";
import Body from "@/components/Body";
export default {
  name: 'Home',
  components: {
    DefaultLayout,
    Body,
  }
}
</script>
