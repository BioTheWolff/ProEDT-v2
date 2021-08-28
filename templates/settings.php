<?php

$this->layout('_template', ['page_title' => 'Paramètres']) ?>

<v-main>
  <v-container>
    <v-select :items="['s1', 's2', 's3', 's4', 's5', 's6', 'q1', 'q2', 'q3', 'q4', 'q5']" label="Votre groupe de TD"
      v-on:change="saveGroupe" :value="groupe"></v-select>
  </v-container>
  <v-main>