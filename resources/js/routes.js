import Home from './components/Home'
import Category from './components/Category'
import Product from './components/Product'
import Color from './components/Color'
import Size from './components/Size'

export const routes = [
    {path: '/', name: 'Home', component: Home},
    {path: '/category', name: 'Category', component: Category},
    {path: '/category/product', name: 'Product', component: Product},
    {path: '/color', name: 'Color', component: Color},
    {path: '/size', name: 'Size', component: Size},
];