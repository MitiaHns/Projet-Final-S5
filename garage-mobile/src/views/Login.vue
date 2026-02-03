<template>
  <IonPage>
    <IonContent class="ion-padding auth-page">
      <div class="auth-wrapper">
        <div class="auth-header">
          <h1>Bon retour !</h1>
          <p>Connectez-vous pour gérer vos véhicules.</p>
        </div>

        <div class="auth-card">
          <IonItem lines="none" class="custom-item">
            <IonLabel position="stacked">Email</IonLabel>
            <IonInput v-model="email" type="email" placeholder="votre@email.com" class="custom-input" />
          </IonItem>

          <IonItem lines="none" class="custom-item">
            <IonLabel position="stacked">Mot de passe</IonLabel>
            <IonInput v-model="password" type="password" placeholder="••••••••" class="custom-input" />
          </IonItem>

          <IonButton expand="block" shape="round" class="login-btn" @click="loginUser">
            Se connecter
          </IonButton>

          <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

          <div class="auth-footer">
            <p>Pas encore de compte ?</p>
            <IonButton fill="clear" @click="router.push('/register')" class="switch-btn">
              S'inscrire
            </IonButton>
          </div>
        </div>
      </div>
    </IonContent>
  </IonPage>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { 
  IonPage, IonContent, 
  IonItem, IonLabel, IonInput, IonButton 
} from '@ionic/vue';
import { auth } from '../firebase';
import { signInWithEmailAndPassword } from 'firebase/auth';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const errorMessage = ref('');

const router = useRouter();

const loginUser = async () => {
  errorMessage.value = '';
  try {
    const userCredential = await signInWithEmailAndPassword(auth, email.value, password.value);
    console.log('Utilisateur connecté :', userCredential.user);
    router.push('/home'); 
  } catch (error: any) {
    errorMessage.value = error.message;
    console.error(error);
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

.auth-header {
  text-align: center;
  margin-bottom: 40px;
}

.auth-header h1 {
  font-weight: 900;
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #5C4033;
}

.auth-header p {
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
}

.custom-item {
  --background: #FFF9F0;
  --padding-start: 16px;
  --inner-padding-end: 16px;
  margin-bottom: 20px;
  border-radius: 20px;
  border: 3px solid #D7CCC8;
}

.custom-input {
  --padding-start: 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #5C4033;
}

.login-btn {
  margin-top: 30px;
  height: 56px;
  font-weight: 900;
  font-size: 1.2rem;
  --box-shadow: 0 6px 0 #5C4033;
}

.login-btn:active {
  transform: translateY(4px);
  --box-shadow: 0 2px 0 #5C4033;
}

.error-text {
  color: var(--ion-color-danger);
  font-size: 0.9rem;
  font-weight: bold;
  text-align: center;
  margin-top: 15px;
}

.auth-footer {
  text-align: center;
  margin-top: 30px;
}

.auth-footer p {
  font-size: 1rem;
  color: #8D6E63;
  font-weight: 600;
}

.switch-btn {
  font-weight: 800;
  font-size: 1.1rem;
  text-transform: none;
}
</style>
