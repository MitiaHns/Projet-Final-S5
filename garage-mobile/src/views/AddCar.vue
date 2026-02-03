<template>
  <IonPage>
    <IonHeader class="ion-no-border">
      <IonToolbar>
        <IonButtons slot="start">
          <IonBackButton default-href="/home"></IonBackButton>
        </IonButtons>
        <IonTitle class="main-title">Ajouter une Voiture</IonTitle>
      </IonToolbar>
    </IonHeader>

    <IonContent class="ion-padding custom-page">
      <div class="form-wrapper">
        <div class="form-header">
          <h2>Nouveau Véhicule</h2>
          <p>Enregistrez votre voiture pour planifier vos réparations.</p>
        </div>

        <div class="form-card">
          <IonItem lines="none" class="custom-item">
            <IonLabel position="stacked">Nom / Modèle</IonLabel>
            <IonInput v-model="name" placeholder="Ex: Renault Clio 5" class="custom-input" />
          </IonItem>

          <IonButton expand="block" shape="round" class="add-btn" @click="add">
            Confirmer l'ajout
          </IonButton>
        </div>
      </div>
    </IonContent>
  </IonPage>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonItem, IonLabel, IonInput, IonButton, IonButtons, IonBackButton 
} from '@ionic/vue';
import { useClientGarage } from '../composables/useClientGarage';

const name = ref('');
const router = useRouter();
const { addCar } = useClientGarage();

const add = () => {
  if (!name.value.trim()) return;
  addCar(name.value);
  router.push('/home');
};
</script>

<style scoped>
.custom-page {
  --background: transparent;
  display: flex;
  align-items: center;
}

.main-title {
  font-weight: 900;
  font-size: 1.4rem;
  color: #5C4033;
}

.form-wrapper {
  width: 100%;
  padding: 20px 0;
}

.form-header {
  text-align: center;
  margin-bottom: 30px;
}

.form-header h2 {
  font-weight: 900;
  font-size: 1.8rem;
  margin-bottom: 10px;
  color: #5C4033;
}

.form-header p {
  color: #8D6E63;
  font-size: 1.1rem;
  font-weight: 600;
}

.form-card {
  background: white;
  padding: 30px;
  border-radius: 30px;
  box-shadow: 0 12px 0 #D7CCC8;
  border: 4px solid #D7CCC8;
}

.custom-item {
  --background: #FFF9F0;
  --padding-start: 16px;
  --inner-padding-end: 16px;
  margin-bottom: 25px;
  border-radius: 20px;
  border: 3px solid #D7CCC8;
}

.custom-input {
  --padding-start: 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #5C4033;
}

.add-btn {
  margin-top: 10px;
  height: 56px;
  font-weight: 900;
  font-size: 1.2rem;
  --box-shadow: 0 6px 0 #5C4033;
}

.add-btn:active {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #5C4033;
}
</style>
