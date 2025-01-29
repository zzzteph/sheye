<script setup>

import Empty from '../Layout/Empty.vue'
import { useForm } from '@inertiajs/vue3'
defineOptions({  layout: Empty})

const form = useForm({
  email: null,
  password: null
})


</script>

<template>
<section class="section">
    <div class="container">
      <div class="columns is-multiline is-centered">
        <div class="column is-8 is-4-desktop has-text-centered">
          <img class="image" src="../../../images/shrewdeye-1.png" :href="route('index')">
          <h3 class="mb-5 is-size-4 has-text-weight-bold">Sign in</h3>
          <form @submit.prevent="form.post('/login')">
            <div class="field">
              <div class="control">
                <input class="input" type="text" placeholder="E-mail address" v-model="form.email">
              </div>
            </div>
            <div class="field">
              <div class="control">
                <input class="input" type="password" placeholder="Password" v-model="form.password">
              </div>
            </div>
            <button class="button is-primary mb-4 is-fullwidth" :disabled="form.processing">Login</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  </template>