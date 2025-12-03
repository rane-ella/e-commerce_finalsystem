<x-store-layout>
  <style>
    :root{
      --max-w:1200px;
      --accent:#c77d7d; /* rose-ish accent */
      --muted:#6b6b6b;
      --radius:10px;
      --card-bg:#fff;
      --page-bg:#fafafa;
    }
    /* Scoped to this page content to avoid breaking layout */
    .product-page-container {
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color:#222; -webkit-font-smoothing:antialiased;
    }
    .site{
      max-width:var(--max-w); margin:24px auto; padding:16px;
    }
    
    /* main layout */
    .product-hero{display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start;margin-top:16px}

    /* left: image */
    .gallery{background:#fff;border-radius:var(--radius);padding:18px;box-shadow:0 6px 18px rgba(30,30,30,0.06);display:flex;flex-direction:column;gap:12px}
    .main-image{display:flex;align-items:center;justify-content:center;border-radius:8px;height:520px;background:linear-gradient(180deg,#fff,#f8f8f8);overflow:hidden}
    .main-image img{max-width:100%;max-height:100%;object-fit:contain}
    .thumbs{display:flex;gap:10px}
    .thumbs button{width:72px;height:72px;border-radius:8px;border:1px solid #e6e6e6;background:#fff;padding:0;cursor:pointer}
    .thumbs button img{width:100%;height:100%;object-fit:cover;border-radius:6px}

    /* right: product details */
    .product-details{background:transparent}
    .breadcrumb{font-size:13px;color:var(--muted);margin-bottom:6px}
    .title{font-size:28px;font-weight:700;margin:4px 0}
    .rating{display:flex;gap:8px;align-items:center;color:#444;margin-bottom:12px}
    .price{font-size:26px;font-weight:800;color:#1f1f1f;margin:12px 0}
    .short-desc{color:var(--muted);line-height:1.6;margin-bottom:18px}

    .options{margin:12px 0}
    .option-group{margin-bottom:10px}
    .option-group label{display:block;font-size:13px;margin-bottom:8px;color:var(--muted)}
    .size-options{display:flex;gap:10px;flex-wrap:wrap}
    .size-chip{padding:10px 14px;border-radius:8px;border:1px solid #e0e0e0;background:#fff;cursor:pointer;font-weight:600}
    .size-chip.selected{border-color:var(--accent);box-shadow:0 6px 18px rgba(199,125,125,0.12);background:linear-gradient(180deg,#fff,#fff)}
    /* Hide radio buttons but keep them functional */
    .size-chip input { display: none; }

    .qty-cta{display:flex;gap:14px;align-items:center;margin-top:16px}
    .qty{display:flex;align-items:center;border-radius:10px;overflow:hidden;border:1px solid #e6e6e6;background:#fff}
    .qty button{border:0;background:transparent;padding:10px 12px;cursor:pointer}
    .qty input{width:54px;border:0;text-align:center;font-weight:700}

    .cta{display:flex;gap:12px;margin-top:10px;flex:1}
    .btn{padding:14px 20px;border-radius:12px;border:0;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center}
    .btn-primary{background:var(--accent);color:#fff;font-size:16px;width:100%}
    .btn-outline{background:transparent;border:1px solid #dcdcdc;color:#333}

    .meta{margin-top:18px;color:var(--muted);font-size:13px}

    .details-section{margin-top:28px;background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(10,10,10,0.03)}
    .details-section h3{margin:0 0 8px 0}
    .details-section p{color:var(--muted);line-height:1.7}

    /* responsive */
    @media (max-width:900px){
      .product-hero{grid-template-columns:1fr;}
      .main-image{height:420px}
    }

    @media (max-width:480px){
      .main-image{height:320px}
      .title{font-size:20px}
      .price{font-size:20px}
    }
  </style>

  <div class="product-page-container">
    <div class="site">
      <main>
        <div class="product-hero">
          <div class="gallery">
            @php
                $imageUrl = $product->images->isNotEmpty() 
                    ? asset('storage/' . $product->images->first()->image_path) 
                    : asset('images/soft-matte-lip-cream.png');
            @endphp
            <div class="main-image" id="mainImage">
              <img src="{{ $imageUrl }}" alt="{{ $product->name }}" />
            </div>

            <!-- Thumbnails (Placeholder logic as we only have one image per product currently) -->
            <div class="thumbs" role="tablist" aria-label="Product thumbnails">
               <button onclick="setMain('{{ $imageUrl }}')"><img src="{{ $imageUrl }}" alt="Main"/></button>
               <!-- Add more placeholders if needed or hide if only 1 image -->
            </div>
          </div>

            <div class="product-details" 
                 x-data="{ 
                    basePrice: {{ $product->price }}, 
                    selectedSize: '30ml',
                    increments: {
                        '30ml': 0,
                        '60ml': 50,
                        '80ml': 100,
                        '100ml': 150
                    },
                    get currentPrice() {
                        return (this.basePrice + this.increments[this.selectedSize]).toFixed(2);
                    },
                    formatPrice(price) {
                        return '₱' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }
                 }">
            <nav class="breadcrumb">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> &gt; 
                <a href="{{ route('categories.show', $product->category->slug ?? 'all') }}" class="hover:underline">{{ $product->category->name ?? 'Category' }}</a> &gt; 
                {{ $product->name }}
            </nav>
            <h1 class="title">{{ $product->name }}</h1>

            <div class="rating">
              <div aria-hidden style="color:#f5b301">
                @for ($i = 0; $i < 5; $i++)
                    {{ $i < floor($product->rating) ? '★' : '☆' }}
                @endfor
              </div>
              <div style="font-size:14px;color:var(--muted)">{{ $product->rating }} • {{ $product->reviews_count }} Reviews</div>
            </div>

            <div class="price" x-text="formatPrice(currentPrice)">₱{{ number_format($product->price, 2) }}</div>

            <form action="{{ route('cart.add', $product) }}" method="POST" id="addToCartForm">
                @csrf
                <div class="options">
                  <div class="option-group">
                    <label>Size / Volume</label>
                    <div class="size-options" role="radiogroup" aria-label="Size options">
                      <!-- Using labels as buttons for radio inputs -->
                      <label class="size-chip" :class="{ 'selected': selectedSize === '30ml' }">
                          <input type="radio" name="size" value="30ml" x-model="selectedSize"> 30 ML
                      </label>
                      <label class="size-chip" :class="{ 'selected': selectedSize === '60ml' }">
                          <input type="radio" name="size" value="60ml" x-model="selectedSize"> 60 ML
                      </label>
                      <label class="size-chip" :class="{ 'selected': selectedSize === '80ml' }">
                          <input type="radio" name="size" value="80ml" x-model="selectedSize"> 80 ML
                      </label>
                      <label class="size-chip" :class="{ 'selected': selectedSize === '100ml' }">
                          <input type="radio" name="size" value="100ml" x-model="selectedSize"> 100 ML
                      </label>
                    </div>
                  </div>

                  <div class="qty-cta">
                    <div class="qty" aria-label="Quantity picker">
                      <button type="button" onclick="changeQty(-1)">−</button>
                      <input id="qtyInput" type="text" name="quantity" value="1" aria-label="Quantity" readonly />
                      <button type="button" onclick="changeQty(1)">+</button>
                    </div>

                      <div class="cta">
                        @if($product->stock > 0)
                            <button type="submit" class="btn btn-primary">Add to cart</button>
                        @else
                            <button type="button" class="btn btn-primary" disabled style="background-color: #ccc; cursor: not-allowed;">Out of Stock</button>
                        @endif
                      </div>
                  </div>

                  <div class="meta">
                      @if($product->stock > 0)
                          In stock • Free shipping over ₱1,500
                      @else
                          Out of Stock
                      @endif
                  </div>
                </div>
            </form>

          </div>
        </div>

        <section class="details-section" style="margin-top:24px">
          <h3>Full description</h3>
          <p>{{ $product->description }}</p>
        </section>

      </main>
    </div>
  </div>

  <script>
    // Size selection logic
    document.querySelectorAll('.size-chip').forEach(chip => {
      chip.addEventListener('click', (e) => {
        // Remove selected class from all
        document.querySelectorAll('.size-chip').forEach(c => c.classList.remove('selected'));
        // Add to clicked
        chip.classList.add('selected');
        // Ensure the radio input inside is checked (handled by label default behavior, but good to be explicit if needed)
      });
    });

    function changeQty(delta){
      const el = document.getElementById('qtyInput');
      let v = parseInt(el.value)||1; 
      v += delta; 
      if(v<1) v=1; 
      el.value=v;
    }

    function setMain(src){
      const img = document.querySelector('#mainImage img');
      img.src = src;
    }
  </script>
</x-store-layout>
