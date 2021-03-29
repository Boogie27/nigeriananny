<?php

class Cart{

    public $_items = null,
            $_totalPrice = 0,
            $_totalQty = 0,
            $_totalShipping = 0;

    public function __construct($oldCart = null)
    {
        if($oldCart)
        {
            $this->_items = $oldCart->_items;
            $this->_totalPrice = $oldCart->_totalPrice;
            $this->_totalQty = $oldCart->_totalQty;
            $this->_totalShipping = $oldCart->_totalShipping;
        }
    }



    public function add($id, $product, $quantity)
    {
        $stored_item = ['id' => $id, 'product' => $product, 'price' => $product->product_price, 'quantity' => 0, 'total' => 0];
        
        if($this->_items)
        {
            if(array_key_exists($id, $this->_items))
            {
                $stored_item = $this->_items[$id];
            }
        }
        
        $stored_item['quantity'] += $quantity;
        $stored_item['total'] += $stored_item['price'] * $quantity;
        $this->_totalPrice += $stored_item['price'] * $quantity;
        $this->_totalQty += $quantity;
        $this->_totalShipping += $product->shipping_fee;
        $this->_items[$id] = $stored_item;
    }




    public function update_quantity($id, $product, $quantity)
    {
        if($this->_items)
        {
            if(array_key_exists($id, $this->_items))
            {
                $stored_item = $this->_items[$id];
                $oldQty = $stored_item['quantity'];
                $oldTotal =  $stored_item['total'];

                $stored_item['quantity'] = $quantity;
                $stored_item['total'] = $quantity * $product->product_price;

                $this->_totalPrice -= $oldTotal;
                $this->_totalQty -= $oldQty;
                $this->_totalShipping -= $oldQty * $product->shipping_fee;

                $this->_totalPrice +=  $quantity * $product->product_price;
                $this->_totalQty += $quantity;
                $this->_totalShipping += $quantity * $product->shipping_fee;

                $this->_items[$id] = $stored_item;
            }
        }
    }





    public function delete_item($id)
    {
        if($id)
        {
            if($this->_items)
            {
                if(array_key_exists($id, $this->_items))
                {
                    $stored_item = $this->_items[$id];

                    $this->_totalPrice -= $stored_item['total'];
                    $this->_totalQty -= $stored_item['quantity'];
                    $this->_totalShipping -= $stored_item['quantity'] * $stored_item['product']->shipping_fee;
                    unset($this->_items[$id]);
                }
            }
        }
    }




// end
}