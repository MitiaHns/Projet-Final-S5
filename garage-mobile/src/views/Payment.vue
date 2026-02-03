<template>
  <IonPage>
    <IonContent class="ion-padding auth-page">
      <div class="auth-wrapper">
        <div v-if="!paid" class="payment-setup">
          <div class="success-header">
            <div class="success-icon">
              <IonIcon :icon="cardOutline" color="primary"></IonIcon>
            </div>
            <h1>Paiement</h1>
            <p>Confirmez le règlement pour <strong>{{ car?.name }}</strong></p>
          </div>

          <div class="auth-card">
            <div class="payment-details">
              <div class="price-row">
                <span>Total à payer</span>
                <span class="price-total">{{ car ? totalPrice(car).toLocaleString() : 0 }} Ar</span>
              </div>
            </div>

            <IonButton expand="block" shape="round" class="pay-btn" @click="handlePayment">
              Confirmer le paiement
            </IonButton>
            
            <IonButton expand="block" fill="clear" color="medium" @click="router.back()">
              Annuler
            </IonButton>
          </div>
        </div>

        <div v-else class="success-view">
          <div class="success-header">
            <div class="success-icon">
              <IonIcon :icon="checkmarkCircleOutline" color="success"></IonIcon>
            </div>
            <h1>Merci !</h1>
            <p>Votre paiement a été validé avec succès.</p>
          </div>

          <div class="auth-card">
            <div class="payment-details">
              <p><strong>Référence :</strong> #PAY-{{ Date.now().toString().slice(-6) }}</p>
              <p><strong>Date :</strong> {{ new Date().toLocaleDateString() }}</p>
            </div>

            <IonButton expand="block" shape="round" class="home-btn" @click="router.push('/home')">
              Retour à l'accueil
            </IonButton>
          </div>
        </div>
      </div>
    </IonContent>
  </IonPage>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { 
  IonPage, IonContent, IonButton, IonIcon 
} from '@ionic/vue';
import { cardOutline, checkmarkCircleOutline } from 'ionicons/icons';
import { useRoute, useRouter } from 'vue-router';
import { useClientGarage } from '../composables/useClientGarage';

const route = useRoute();
const router = useRouter();
const { cars, totalPrice, payForCar } = useClientGarage();

const paid = ref(false);

const car = computed(() => 
  cars.value.find(c => c.id === String(route.params.id))
);

const handlePayment = () => {
  if (car.value) {
    payForCar(car.value.id);
    paid.value = true;
  }
};
</script>

<style scoped>
.auth-page {
  --background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}

.auth-wrapper {
  width: 100%;
  max-width: 400px;
  padding: 20px;
}

.success-header {
  text-align: center;
  margin-bottom: 40px;
}

.success-icon {
  font-size: 5rem;
  margin-bottom: 10px;
}

.success-header h1 {
  font-weight: 900;
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #3E2723;
}

.success-header p {
  color: #8D6E63;
  font-size: 1.1rem;
  font-weight: 600;
}

.auth-card {
  background: white;
  padding: 30px;
  border-radius: 30px;
  box-shadow: 0 12px 0 #D7CCC8;
  border: 4px solid #D7CCC8;
  text-align: center;
}

.payment-details {
  margin-bottom: 30px;
  padding: 20px;
  background: #FFF9F0;
  border-radius: 20px;
  border: 3px solid #D7CCC8;
}

.payment-details p {
  margin: 10px 0;
  font-size: 1rem;
  font-weight: 600;
  color: #8D6E63;
}

.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
}

.price-total {
  font-size: 1.8rem;
  font-weight: 900;
  color: #8D6E63;
}

.pay-btn {
  height: 56px;
  font-weight: 900;
  font-size: 1.2rem;
  margin-top: 10px;
  --box-shadow: 0 6px 0 #5C4033;
}

.pay-btn:active {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #5C4033;
}

.home-btn {
  height: 56px;
  font-weight: 900;
  font-size: 1.2rem;
  --box-shadow: 0 6px 0 #5C4033;
}

.home-btn:active {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #5C4033;
}
</style>
