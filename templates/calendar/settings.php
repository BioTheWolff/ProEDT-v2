<?php

$this->layout('calendar/_template', ['page_title' => 'Paramètres']) ?>

<v-main>
  <v-container>
      <v-card elevation="2" class="margin-10">
          <v-card-title>Votre groupe</v-card-title>
          <v-card-subtitle>
              Veuillez choisir votre groupe de TD pour accéder à l'emploi du temps, au devoirs et informations.<br>
              Une fois sélectionné, vous pouvez retourner à l'edt en cliquant sur <v-icon dense class="nav-icon">mdi-calendar-outline</v-icon> en haut à droite.
          </v-card-subtitle>
          <v-card-text>
              <v-select :items="['s1', 's2', 's3', 's4', 's5', 's6', 'q1', 'q2', 'q3', 'q4', 'q5']"
                        v-on:change="saveGroupe" :value="groupe"></v-select>
          </v-card-text>
      </v-card>
  </v-container>
  <v-main>