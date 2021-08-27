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
        :event-overlap-threshold="30" @change="getEvents" @click:event="showEvent" start="2021-06-10">
        <template v-slot:event="{ event }">
          <div class="pl-1">
            <strong>{{ event.name }} | {{ ("0" + event.start.getHours()).slice(-2) }}h{{ ("0" + event.start.getMinutes()).slice(-2) }}-{{ ("0" + event.end.getHours()).slice(-2) }}h{{ ("0" + event.end.getMinutes()).slice(-2) }}</strong>
            <br>
            {{ event.location }}
          </div>
        </template>
      </v-calendar>

      <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="350px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
            </v-toolbar>
            <v-card-text v-if="selectedElement" style="color: black;">
              De {{ ("0" + selectedEvent.start.getHours()).slice(-2) }}h{{ ("0" + selectedEvent.start.getMinutes()).slice(-2) }} à {{ ("0" + selectedEvent.end.getHours()).slice(-2) }}h{{ ("0" + selectedEvent.end.getMinutes()).slice(-2) }}
              <br>
              Avec {{selectedEvent.teachers}}
              <br>
              En {{selectedEvent.location}}
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="selectedOpen = false"
              >
                Fermer
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>

    </v-sheet>
  </v-container>
</v-main>