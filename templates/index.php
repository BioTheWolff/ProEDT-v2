<?php

$this->layout('_template') ?>

<v-main>
  <v-container>
    <v-sheet tile height="54" class="d-flex">
      <v-btn icon class="ma-2" @click="$refs.calendar.prev()">
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
      <v-spacer></v-spacer>
      <v-toolbar-title v-if="$refs.calendar">
        {{ $refs.calendar.title }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon class="ma-2" @click="$refs.calendar.next()">
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </v-sheet>
    <v-sheet height="600">
      <v-calendar ref="calendar" v-model="value" :weekdays="weekday" :type="type" first-time="7" locale="fr"
        interval-count="12" interval-height="40" :events="events" :event-overlap-mode="mode"
        :event-overlap-threshold="30" @change="getEvents"></v-calendar>
    </v-sheet>
  </v-container>
</v-main>