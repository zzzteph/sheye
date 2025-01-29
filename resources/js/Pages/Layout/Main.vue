<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <main>
    <header>
      <nav class="navbar py-4">
      <div class="container is-fluid">
        <div class="navbar-brand">
          <a class="navbar-item" href="#">
            <img class="image" src="../../../images/shrewdeye-1.png">
          </a>
          <a class="navbar-burger" role="button" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </a>
        </div>
        <div class="navbar-menu">
          <div class="navbar-end"><a class="navbar-item" href="#">About</a>
            <a class="navbar-item" href="#">Dashboard</a>
          </div>
          <div class="navbar-item">
            <div class="buttons"><a class="button" href="#">Sign In</a>
              <a class="button is-primary" href="#">Sign Out</a></div>
          </div>
        </div>
      </div>
    </nav>
    </header>
    <article>
      <slot />
    </article>
  </main>
</template>