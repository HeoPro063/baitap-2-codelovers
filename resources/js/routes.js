import Home from './components/Home'
import Category from './components/Category'
import Product from './components/Product'

export const routes = [
    {path: '/', name: 'Home', component: Home},
    {path: '/category', name: 'Category', component: Category},
    {path: '/product', name: 'Product', component: Product},
];