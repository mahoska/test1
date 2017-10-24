import Vue from 'vue'
import Router from 'vue-router'
import General from '@/components/General'
import GeneralAdmin from '@/components/GeneralAdmin'
import Registration from '@/components/Registration'
import Cart from '@/components/Cart'
import Book from '@/components/Book'
import Payment from '@/components/Payment'
import Order from '@/components/Order'

import AddGanre from '@/components/AddGanre'
import AddAuthor from '@/components/AddAuthor'
import AddBook from '@/components/AddBook'
import EditGanre from '@/components/EditGanre'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'General',
      component: General
    },
    {
      path: '/admin/',
      name: 'GeneralAdmin',
      component: GeneralAdmin,
      children: [
        { path: 'addGanre', name: 'addGanre',component: AddGanre },
        { path: 'addAuthor', name: 'addAuthor',component: AddAuthor },
        { path: 'addBook', name: 'addBook',component: AddBook },
        { path: 'editGanre/:id', name: 'editGanre',component: EditGanre },
      ]
    },
    {
      path: '/registration',
      name: 'Registration',
      component: Registration
    },
    {
        path: '/cart',
        name: 'Cart',
        component: Cart
      },
    {
      path: '/book/:book_id',
      name: 'Book',
      component: Book
    },
    {
      path: '/payment',
      name: 'Payment',
      component: Payment
    },
    {
      path: '/order',
      name: 'Order',
      component: Order
    },
  ]
})
