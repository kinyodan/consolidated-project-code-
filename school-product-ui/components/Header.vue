<template>
  <header>
    <LogoComponent></LogoComponent>
    <div class="app_header_right">
      <school-switcher></school-switcher>
      <LoginComponent></LoginComponent>
    </div>
  </header>
</template>

<script>
import LogoComponent from "./HeaderComponents/LogoComponent";
import LoginComponent from "./HeaderComponents/LoginComponent";
import SchoolSwitcher from "@/components/HeaderComponents/SchoolSwitcher";

export default {
  name: "Header",
  components: {
    SchoolSwitcher,
    LogoComponent,
    LoginComponent
  },
  data() {
    return {}
  },
}
</script>

<style>

</style>
