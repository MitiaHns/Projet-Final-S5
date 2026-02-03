import { ref, computed } from 'vue';
import { toastController } from '@ionic/vue';
import { db, auth } from '../firebase';
import { 
  collection, 
  addDoc, 
  getDocs, 
  query, 
  where,
  doc,
  Timestamp,
  writeBatch
} from 'firebase/firestore';

export interface Repair {
  id: string;
  name: string;
  price: number;
  selected: boolean;
}

export interface Car {
  id: string;
  name: string;
  status: string;
  repairs: Repair[];
}

const cars = ref<Car[]>([]);

export const useClientGarage = () => {
  
  // 1. CHARGER LES VOITURES depuis Firebase
  const loadCars = async () => {
    const user = auth.currentUser;
    if (!user) return;

    try {
      // Récupérer les voitures de l'utilisateur
      const carsSnapshot = await getDocs(
        query(collection(db, 'cars'), 
        where('userId', '==', user.uid))
      );
      
      cars.value = carsSnapshot.docs.map(doc => ({
        id: doc.id,
        name: doc.data().model,
        status: 'En attente',
        repairs: [
          { id: '1', name: 'Vidange moteur', price: 50000, selected: false },
          { id: '2', name: 'Freins', price: 80000, selected: false },
          { id: '3', name: 'Pneumatiques', price: 120000, selected: false },
          { id: '4', name: 'Climatisation', price: 60000, selected: false }
        ]
      }));
    } catch (error) {
      console.error('Erreur:', error);
    }
  };

  // 2. AJOUTER UNE VOITURE
  const addCar = async (name: string) => {
    const user = auth.currentUser;
    if (!user) return;

    try {
      await addDoc(collection(db, 'cars'), {
        model: name,
        userId: user.uid,
        plate_number: 'NC',
        created_at: Timestamp.now()
      });
      
      await loadCars(); // Recharger la liste
      
      const toast = await toastController.create({
        message: '✅ Voiture ajoutée !',
        duration: 2000,
        color: 'success'
      });
      await toast.present();
    } catch (error) {
      console.error('Erreur:', error);
    }
  };

  // 3. CONFIRMER LES RÉPARATIONS
  const confirmRepairs = (car: Car) => {
    car.status = 'En réparation';
    
    setTimeout(async () => {
      car.status = 'Prête';
      const toast = await toastController.create({
        message: `🏁 ${car.name} est prête !`,
        duration: 3000,
        color: 'success'
      });
      await toast.present();
    }, 5000);
  };

  // 4. CALCULER LE TOTAL
  const totalPrice = (car: Car) => {
    return car.repairs
      .filter(r => r.selected)
      .reduce((sum, r) => sum + r.price, 0);
  };

  // 5. PAYER
  const payForCar = async (carId: string) => {
    const car = cars.value.find(c => c.id === carId);
    if (car) {
      car.status = 'Payée';
      
      // Enregistrer le paiement dans Firebase
      await addDoc(collection(db, 'payments'), {
        carId: carId,
        amount: totalPrice(car),
        userId: auth.currentUser?.uid,
        payment_date: Timestamp.now()
      });
    }
  };

  const revenue = computed(() => 0);
  const repairStats = computed(() => ({}));

  return {
    cars,
    addCar,
    confirmRepairs,
    totalPrice,
    payForCar,
    revenue,
    repairStats,
    loadCars
  };
};