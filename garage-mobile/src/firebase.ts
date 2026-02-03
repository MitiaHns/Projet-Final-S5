import { initializeApp } from "firebase/app";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";

const firebaseConfig = {
    apiKey: "AIzaSyBFTn9gB2-u3EHAewToZSK15RYVAPiKANw",
    authDomain: "garage-308e4.firebaseapp.com",
    projectId: "garage-308e4",
    storageBucket: "garage-308e4.firebasestorage.app",
    messagingSenderId: "268117625820",
    appId: "1:268117625820:web:d7fd341f52cd7c61b1dd19"
};

const app = initializeApp(firebaseConfig);

export const auth = getAuth(app);
export const db = getFirestore(app);
