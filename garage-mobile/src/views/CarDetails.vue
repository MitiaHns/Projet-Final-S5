<template>
  <IonPage v-if="car">
    <IonHeader class="ion-no-border">
      <IonToolbar>
        <IonButtons slot="start">
          <IonBackButton default-href="/home"></IonBackButton>
        </IonButtons>
        <IonTitle class="main-title">{{ car.name }}</IonTitle>
      </IonToolbar>
    </IonHeader>

    <IonContent class="ion-padding custom-page">
      <div class="header-section">
        <IonBadge :color="color(car.status)" class="status-badge">{{ car.status }}</IonBadge>
        <h2>Choisir les réparations</h2>
        <p>Sélectionnez les services nécessaires pour votre véhicule.</p>
      </div>

      <div class="repair-list">
        <IonItem v-for="r in car.repairs" :key="r.id" lines="none" class="repair-item">
          <IonCheckbox slot="start" v-model="r.selected" class="custom-checkbox"></IonCheckbox>
          <IonLabel>
            <h3 class="repair-name">{{ r.name }}</h3>
            <p class="repair-price">{{ r.price.toLocaleString() }} Ar</p>
          </IonLabel>
        </IonItem>
      </div>

      <div class="summary-card">
        <div class="total-row">
          <span>Total</span>
          <span class="total-amount">{{ total.toLocaleString() }} Ar</span>
        </div>
        
        <IonButton expand="block" shape="round" color="primary" class="confirm-btn" @click="confirm" :disabled="total === 0">
          Confirmer les réparations
        </IonButton>

        <IonButton
          v-if="car.status === 'Prête'"
          color="success"
          expand="block"
          shape="round"
          class="pay-btn"
          @click="router.push(`/payment/${car.id}`)"
        >
          💳 Payer maintenant
        </IonButton>
      </div>
    </IonContent>
  </IonPage>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonItem, IonLabel, IonCheckbox, IonButton, 
  IonButtons, IonBackButton, IonBadge 
} from '@ionic/vue';
import { useClientGarage } from '../composables/useClientGarage';

const route = useRoute();
const router = useRouter();
const { cars, confirmRepairs, totalPrice } = useClientGarage();

const car = computed(() =>
  cars.value.find(c => c.id === String(route.params.id))
);

const total = computed(() => car.value ? totalPrice(car.value) : 0);

const color = (s: string) =>
  s === 'Prête' ? 'success' : s === 'En réparation' ? 'warning' : 'medium';

const confirm = () => {
  if (car.value) {
    confirmRepairs(car.value);
    router.push('/home');
  }
};
</script>

<style scoped>
.custom-page {
  --background: transparent;
}

.main-title {
  font-weight: 900;
  font-size: 1.4rem;
  color: #5C4033;
}

.header-section {
  text-align: center;
  margin-top: 10px;
  margin-bottom: 30px;
}

.status-badge {
  padding: 10px 16px;
  border-radius: 12px;
  text-transform: uppercase;
  font-size: 0.8rem;
  font-weight: 800;
  margin-bottom: 15px;
}

.header-section h2 {
  font-weight: 900;
  font-size: 1.8rem;
  margin-bottom: 8px;
  color: #5C4033;
}

.header-section p {
  color: #8D6E63;
  font-weight: 600;
}

.repair-list {
  margin-bottom: 180px;
}

.repair-item {
  --background: white;
  margin-bottom: 15px;
  border-radius: 24px;
  --inner-padding-end: 16px;
  box-shadow: 0 8px 0 #D7CCC8;
  border: 4px solid #D7CCC8;
}

.repair-name {
  font-weight: 800;
  font-size: 1.1rem;
  color: #3E2723;
}

.repair-price {
  font-weight: 900;
  color: #8D6E63;
  font-size: 1rem;
}

.custom-checkbox {
  --border-radius: 8px;
  margin-right: 16px;
}

.summary-card {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: white;
  padding: 25px;
  border-radius: 30px 30px 0 0;
  box-shadow: 0 -15px 40px rgba(62, 39, 35, 0.1);
  border-top: 4px solid #D7CCC8;
  z-index: 100;
}

.total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  padding: 0 10px;
}

.total-row span:first-child {
  font-size: 1.2rem;
  font-weight: 800;
  color: #8D6E63;
}

.total-amount {
  font-size: 1.8rem;
  font-weight: 900;
  color: #5C4033;
}

.confirm-btn {
  height: 56px;
  font-weight: 900;
  font-size: 1.1rem;
  --box-shadow: 0 6px 0 #5C4033;
}

.confirm-btn:active:not(:disabled) {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #5C4033;
}

.pay-btn {
  margin-top: 15px;
  height: 56px;
  font-weight: 900;
  font-size: 1.1rem;
  --box-shadow: 0 6px 0 #2d5a27;
}

.pay-btn:active {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #2d5a27;
}
</style>
