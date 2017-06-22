<?php
namespace EmizenTech\InvoicePdfimage\Model\Magento\Sales\Order\Pdf\Items\Invoice;

class DefaultInvoice extends \Magento\Sales\Model\Order\Pdf\Items\Invoice\DefaultInvoice
{
    public function draw()
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];


        // draw Product image
        $productImage = $this->getProductImage($item, $page);

        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName(), 35, true, true), 'feed' => 35]];

        $lines[0][] = array(
            'text'  => $productImage,
            'is_image'  => 1,
            'feed'  => 200
        );

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 17),
            'feed' => 370,
            'align' => 'right',
        ];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 475, 'align' => 'right'];

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = 425;
        $feedSubtotal = $feedPrice + 140;
        foreach ($prices as $priceData) {
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
                // draw Subtotal label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                'text' => $priceData['price'],
                'feed' => $feedPrice,
                'font' => 'bold',
                'align' => 'right',
            ];
            // draw Subtotal
            $lines[$i][] = [
                'text' => $priceData['subtotal'],
                'feed' => $feedSubtotal,
                'font' => 'bold',
                'align' => 'right',
            ];
            $i++;
        }

        // draw Tax
        $lines[0][] = [
            'text' => $order->formatPriceTxt($item->getTaxAmount()),
            'feed' => 515,
            'font' => 'bold',
            'align' => 'right',
        ];

        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                if ($option['value']) {
                    if (isset($option['print_value'])) {
                        $printValue = $option['print_value'];
                    } else {
                        $printValue = $this->filterManager->stripTags($option['value']);
                    }
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                    }
                }
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];
        
        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true],1);
        
        $this->setPage($page);
    }


    /*
     * Return Value of custom attribute
     * */
    private function getProductImage($item,  &$page)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productId = $item->getOrderItem()->getProductId();
        $image = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);

        if (!is_null($image)) {
            try{

                $imagePath = '/catalog/product/'.$image->getSmallImage();

                $filesystem = $objectManager->get('Magento\Framework\Filesystem');
                $media_dir = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
               
                if ($media_dir->isFile($imagePath)) {
                    return $media_dir->getAbsolutePath($imagePath);
                }
                else
                    return null;
            }
            catch (Exception $e) {
                return false;
            }
        }
    }
}
