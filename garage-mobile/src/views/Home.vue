<template>
  <IonPage>
    <IonHeader class="ion-no-border main-header">
      <IonToolbar>
        <IonButtons slot="start" class="header-icon-btn">
          <IonIcon :icon="carOutline" color="primary"></IonIcon>
        </IonButtons>
        <IonTitle class="main-title">Gestion Garage</IonTitle>
        <IonButtons slot="end">
          <IonButton color="danger" fill="clear" @click="handleLogout">
            <IonIcon slot="icon-only" :icon="logOutOutline"></IonIcon>
          </IonButton>
        </IonButtons>
      </IonToolbar>

      <div class="filter-wrapper">
        <IonSegment v-model="selectedFilter" mode="ios" class="custom-segment">
          <IonSegmentButton value="Toutes">
            <IonLabel>Tout</IonLabel>
          </IonSegmentButton>
          <IonSegmentButton value="En attente">
            <IonLabel>Attente</IonLabel>
          </IonSegmentButton>
          <IonSegmentButton value="En réparation">
            <IonLabel>Atelier</IonLabel>
          </IonSegmentButton>
          <IonSegmentButton value="Prête">
            <IonLabel>Prêts</IonLabel>
          </IonSegmentButton>
          <IonSegmentButton value="Payée">
            <IonLabel>Payés</IonLabel>
          </IonSegmentButton>
        </IonSegment>
      </div>
    </IonHeader>

    <IonContent class="ion-padding custom-page">
      <div class="app-container">
        <div class="car-list">
          <IonCard v-for="car in filteredCars" :key="car.id" class="modern-card">
            <IonCardHeader>
              <div class="card-top">
                <IonCardTitle class="car-name">{{ car.name }}</IonCardTitle>
                <IonBadge :color="color(car.status)" class="status-badge">{{ car.status }}</IonBadge>
              </div>
            </IonCardHeader>

            <IonCardContent>
              <div class="car-details">
                <p>Somme réparations : <strong>{{ totalPrice(car).toLocaleString() }} Ar</strong></p>
              </div>
              <IonButton expand="block" shape="round" fill="solid" class="action-btn" @click="goDetails(car.id)">
                Gérer les réparations
              </IonButton>
            </IonCardContent>
          </IonCard>
        </div>
      </div>

      <IonFab vertical="bottom" horizontal="end" slot="fixed">
        <IonFabButton @click="router.push('/add-car')" color="primary">
          <IonIcon :icon="addOutline"></IonIcon>
        </IonFabButton>
      </IonFab>
    </IonContent>
  </IonPage>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonCard, IonCardHeader, IonCardTitle, IonCardContent, 
  IonBadge, IonButton, IonButtons, IonIcon, IonSegment, 
  IonSegmentButton, IonLabel, IonFab, IonFabButton
} from '@ionic/vue';
import { logOutOutline, addOutline, carOutline } from 'ionicons/icons';
import { useRouter } from 'vue-router';
import { useClientGarage } from '../composables/useClientGarage';
import { auth } from '../firebase';
import { signOut } from 'firebase/auth';

const router = useRouter();
const { cars, totalPrice, revenue, repairStats, loadCars } = useClientGarage();

const selectedFilter = ref('Toutes');

const filteredCars = computed(() => {
  if (selectedFilter.value === 'Toutes') return cars.value;
  return cars.value.filter(c => c.status === selectedFilter.value);
});

const handleLogout = async () => {
  await signOut(auth);
  router.push('/login');
};

const goDetails = (id: string) => router.push(`/car/${id}`);

const color = (s: string) =>
  s === 'Payée' ? 'success' : s === 'Prête' ? 'primary' : s === 'En réparation' ? 'warning' : 'medium';

onMounted(() => {
  loadCars();
});
</script>

<style scoped>
.main-header {
  background: var(--ion-background-color) !important;
  padding-bottom: 20px;
  border-radius: 0 0 30px 30px;
  box-shadow: 0 10px 0 #D7CCC8;
}

.header-icon-btn {
  margin-left: 15px;
  font-size: 1.5rem;
}

.main-title {
  font-weight: 900;
  color: var(--ion-text-color);
  font-size: 1.3rem;
  padding-left: 10px;
}

.filter-wrapper {
  padding: 10px;
}

.custom-segment {
  --background: #D7CCC8;
  border-radius: 20px;
  padding: 5px;
}

.custom-page {
  --background: transparent;
}

.car-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 25px;
  padding: 20px 0;
}

.modern-card {
  margin: 0;
  border-radius: 24px;
  background: white;
  border: 4px solid #D7CCC8;
  box-shadow: 0 10px 0 #D7CCC8 !important;
  transition: transform 0.2s;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.modern-card:active {
  transform: translateY(4px);
  box-shadow: 0 4px 0 #D7CCC8 !important;
}

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 15px;
}

.car-name {
  font-weight: 900;
  font-size: 1.3rem;
  color: #3E2723;
  line-height: 1.2;
}

.status-badge {
  padding: 8px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
}

.car-details {
  margin: 15px 0 25px 0;
  flex-grow: 1;
}

.car-details p {
  color: #8D6E63;
  font-size: 1rem;
  font-weight: 700;
  margin: 0;
}

.action-btn {
  --box-shadow: none;
  font-weight: 900;
  height: 52px;
  font-size: 1.1rem;
  margin: 0;
}
</style>
