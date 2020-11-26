<template>

</template>
<script>
import { mdiAccount, mdiPencil, mdiShareVariant, mdiDelete } from "@mdi/js";
import Login from "@/components/Login";
import Register from "@/components/Register";
export default {
  name:'Header',
  components:{
    Register,
    Login,
  } ,
  data() {
    return {
      icons: {
        mdiAccount,
        mdiPencil,
        mdiShareVariant,
        mdiDelete,
      },
      dialoglog: false,
      dialogreg:false,
    };
  },
};
</script>

<style>
.header {
  line-height: 30px;
  background-color: #333;
}
</style>
