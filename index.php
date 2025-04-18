<?php
session_start();
require_once './config/database.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'manager') {
        header('Location: ./manager/dashboard.php');
    } else {
        header('Location: ./donor/dashboard.php');
    }
    exit;
}

// Get the base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HelpHub - Plateforme de dons caritatifs</title>
    <meta name="title" content="HelpHub - Plateforme de dons caritatifs" />
    <meta name="description" content="Plateforme de gestion des dons pour associations caritatives" />

    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/owl.carousel.css">
    <link rel="stylesheet" href="/assets/owl.theme.default.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/logo.webp" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/utilities.css" />
</head>
<body>
    <!-- Particles.js Container -->
    <div id="particles-js"></div>

    <header id="hero">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="container">
                <!-- Logo -->
                <h1 id="logo">
                    <div class="header__logo-container">
                        <div class="cont-logo">
                            <img class="logo-profile-image" src="/assets/logo.webp" alt="Logo HelpHub" />
                        </div>
                        <span class="logo-sub"><a class="logo-sub" href="<?php echo $base_url; ?>"> HelpHub</a></span>
                    </div>
                </h1>
                
                <!-- Navbar links -->
                <ul class="nav-menu">
                    <li><a class="nav-link" href="#projects">Projets</a></li>
                    <li><a class="nav-link" href="#associations">Associations</a></li>
                    <li><a class="nav-link" href="#how-it-works">Fonctionnement</a></li>
                    <li class="nav-item dropdown">
                        <span class="nav-link dropdown-toggle">Connexion</span>
                        <ul class="dropdown-menu">
                            <li><a href="auth/login.php">Se connecter</a></li>
                            <li><a href="auth/signup.php">S'inscrire (Donateur)</a></li>
                            <li><a href="auth/signup.php?type=manager">Association</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo isset($_SESSION['user_id']) ? 
                            ($_SESSION['user_type'] === 'manager' ? 'manager/dashboard.php' : 'donor/dashboard.php') : 
                            'auth/login.php'; ?>" 
                            class="btn btn-primary">
                            Faire un don <i class="fas fa-arrow-right"></i>
                        </a>
                    </li>
                    <div class="theme-switch">
                        <input type="checkbox" id="switch" />
                        <label class="toggle-icons" for="switch">
                            <img class="moon" src="/assets/moon.svg" />
                            <img class="sun" src="/assets/sun.svg" />
                        </label>
                    </div>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="header-container">
            <img class="profile-image" src="/assets/hero-donation.webp" alt="Image d'accueil HelpHub" />
            <h1>Changez des vies avec HelpHub</h1>
            <div class="content-text">
                <div class="animated-text">
                    <h2><span id="typewriter"></span></h2>
                </div>
                <p>Plateforme de dons caritatifs connectant généreux donateurs et associations œuvrant pour des causes nobles.</p>
            </div>
            <a href="<?php echo $base_url; ?>/auth/login.php" class="btn btn-secondary">Faire un don</a>
        </section>
    </header>


      <section class="header-container">
        <img
          class="profile-image"
          src="/assets/hero-donation.webp"
          alt="Image d'accueil HelpHub"
        />
        <h1>Changez des vies avec HelpHub</h1>

        <div class="content-text">
          <div class="animated-text">
            <h2><span id="typewriter"></span></h2>
          </div>

          <p>
            Plateforme de dons caritatifs connectant généreux donateurs et associations œuvrant pour des causes nobles.
          </p>
        </div>
        <a
          href="/projects"
          class="btn btn-secondary"
          >Faire un don</a
        >

        <div class="content-text"></div>
      </section>
    </header>

    <!-- introduction -->
    <section id="introduction" class="project-container container">
      <div class="division"></div>
      <div class="content-text">
        <h2>À propos de HelpHub</h2>
        <p>Notre mission est de faciliter le don caritatif</p>
      </div>

      <div class="about__content">
        <!-- "Decouvrir HelpHub" Section -->
        <div class="about__content-main">
          <h3 class="about__content-title">Notre plateforme</h3>
          <div class="about__content-details">
            <p class="about__content-details-para">
              HelpHub est une plateforme innovante qui connecte les donateurs avec des associations caritatives sérieuses. Nous offrons une solution transparente pour soutenir des projets sociaux tout en garantissant un suivi précis des dons.
            </p> 
            <p class="about__content-details-para">
              Notre objectif est de créer un pont numérique entre la générosité des donateurs et les besoins réels des associations œuvrant sur le terrain.
            </p>
          </div>
        </div>

        <!-- Domaines d'intervention Section -->
        <div class="about__content-main">
          <h3 class="about__content-title">Domaines d'intervention</h3>
          <div class="epoques-container">
            <div class="epoque-box">Aide alimentaire</div>
            <div class="epoque-box">Éducation</div>
            <div class="epoque-box">Santé</div>
            <div class="epoque-box">Environnement</div>
            <div class="epoque-box">Droits humains</div>
            <div class="epoque-box">Urgences</div>
          </div>
        </div>
      </div>
    </section>
   
    <!-- Projets en cours -->
    <section id="projects" class="project-container container">
      <div class="divisionin"></div>
      <div class="content-text">
        <h2>Projets en cours</h2>
        <p>Découvrez les projets qui ont besoin de votre soutien</p>
      </div>
      
      <div class="container">
        <!-- Tab Menu -->
        <div class="tabs-container">
            <div class="tab active" onclick="showTimeline(1)">Urgents</div>
            <div class="tab" onclick="showTimeline(2)">Éducation</div>
            <div class="tab" onclick="showTimeline(3)">Santé</div>
        </div>

        <!-- Projets List -->
        <ul class="timeline active" id="timeline1">
            <li class="timeline-event">
                <label class="timeline-event-icon"></label>
                <div class="timeline-event-copy">
                    <p class="timeline-event-thumbnail">Objectif: 50,000 TND</p>
                    <h3>Aide aux victimes des inondations</h3>
                    <p>Ce projet vise à fournir une aide immédiate aux familles touchées par les récentes inondations dans le sud du pays. Les fonds collectés serviront à l'achat de produits de première nécessité, de médicaments et à la reconstruction d'habitations.</p>
                    <div class="progress-bar">
                      <div class="progress" style="width: 65%">65%</div>
                    </div>
                    <a href="/project/1" class="btn btn--med btn--theme dynamicBgClr">Contribuer</a>
                </div>
            </li>
            
            <li class="timeline-event">
                <label class="timeline-event-icon"></label>
                <div class="timeline-event-copy">
                    <p class="timeline-event-thumbnail">Objectif: 30,000 TND</p>
                    <h3>Kits scolaires pour enfants défavorisés</h3>
                    <p>Distribution de 500 kits scolaires complets (cartables, fournitures, uniformes) aux enfants des régions rurales pour la rentrée scolaire prochaine. Ce projet permettra à ces enfants d'aborder l'année scolaire dans de bonnes conditions.</p>
                    <div class="progress-bar">
                      <div class="progress" style="width: 40%">40%</div>
                    </div>
                    <a href="/project/2" class="btn btn--med btn--theme dynamicBgClr">Contribuer</a>
                </div>
            </li>
        </ul>

        <ul class="timeline" id="timeline2">
            <!-- Education projects would be listed here -->
        </ul>

        <ul class="timeline" id="timeline3">
            <!-- Health projects would be listed here -->
        </ul>
      </div>
    </section>

    <!-- Associations partenaires -->
    <section id="associations" class="figures container">
      <div class="divisionin"></div>
      <div class="content-text">
        <h2>Nos associations partenaires</h2>
        <p>Des organisations sérieuses et engagées pour des causes nobles.</p>
      </div>

      <div class="figures">
          <div class="figures__row">
            <div class="figures__row-img-cont">
              <img
                src="/assets/association1.jpg"
                alt="Croissant Rouge Tunisien"
                class="figures__row-img"
                loading="lazy"
              />
            </div>
            <div class="figures__row-content">
              <h3 class="figures__row-content-title">Croissant Rouge Tunisien</h3>
              <p class="figures__row-content-desc">
                Fondé en 1956, le Croissant Rouge Tunisien est une organisation humanitaire qui intervient dans les domaines de la santé, des secours d'urgence et de l'aide sociale. Présent dans tout le pays, il joue un rôle crucial dans l'assistance aux populations vulnérables et dans la gestion des crises.
              </p>
              <div class="btnfigures">
                <a
                  href="/association/1"
                  class="btn btn--med btn--theme dynamicBgClr"
                  >Voir les projets</a
                >
              </div>
            </div>
          </div>
          
          <div class="figures__row">
            <div class="figures__row-img-cont">
              <img
                src="/assets/association2.jpg"
                alt="Association Tunisienne de Protection de la Nature"
                class="figures__row-img"
                loading="lazy"
              />
            </div>
            <div class="figures__row-content">
              <h3 class="figures__row-content-title">ATPN</h3>
              <p class="figures__row-content-desc">
                L'Association Tunisienne de Protection de la Nature œuvre depuis 1988 pour la préservation de l'environnement et la biodiversité en Tunisie. Ses actions incluent la sensibilisation, le reboisement, la protection des espèces menacées et la promotion du développement durable.
              </p>
              <div class="btnfigures">
                <a
                  href="/association/2"
                  class="btn btn--med btn--theme dynamicBgClr"
                  >Voir les projets</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Autres associations -->
    <section id="other-associations" class="figure-container container">
      <div class="divisionin"></div>
      <div class="content-text">
        <h2>Autres associations</h2>
        <p>Découvrez d'autres organisations que vous pouvez soutenir</p>
      </div>

      <div class="logos">
        <!-- Group 1 -->
        <div class="logo-group">
          <img loading="lazy" src="/assets/asso1.jpg" alt="Amal pour l'enfance" class="logo to-top" />
          <img loading="lazy" src="/assets/asso2.jpg" alt="Tunisie Solidarité" class="logo active" />
          <img loading="lazy" src="/assets/asso3.jpg" alt="SOS Villages d'Enfants" class="logo to-bottom" />
        </div>

        <!-- Group 2 -->
        <div class="logo-group">
          <img loading="lazy" src="/assets/asso4.jpg" alt="Association Tunisienne des Maladies Orphelines" class="logo to-top" />
          <img loading="lazy" src="/assets/asso5.jpg" alt="Banque Alimentaire" class="logo active" />
          <img loading="lazy" src="/assets/asso6.jpg" alt="Association de Développement Durable" class="logo to-bottom" />
        </div>
      </div>
    </section>

    <!-- Témoignages -->
    <section class="testimonials" id="testimonials">
      <div class="divisionin"></div>
      <div class="content-text">
        <h2>Témoignages</h2>
        <p>Ce que disent nos donateurs et associations</p>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div id="customers-testimonials" class="owl-carousel">

              <!-- TESTIMONIAL 1 -->
              <div class="item">
                <div class="shadow-effect">
                  <img class="img-circle" src="/assets/donateur1.jpg" alt="Mohamed S.">
                  <p>"Grâce à HelpHub, j'ai pu soutenir plusieurs projets éducatifs en toute confiance. La transparence de la plateforme est remarquable."</p>
                </div>
                <div class="testimonial-name">Mohamed S.</div>
              </div>

              <!-- TESTIMONIAL 2 -->
              <div class="item">
                <div class="shadow-effect">
                  <img class="img-circle" src="/assets/donateur2.jpg" alt="Leila M.">
                  <p>"Je donne régulièrement via HelpHub car je peux suivre l'impact de mes dons. C'est motivant de voir les projets aboutir grâce à notre contribution collective."</p>
                </div>
                <div class="testimonial-name">Leila M.</div>
              </div>

              <!-- TESTIMONIAL 3 -->
              <div class="item">
                <div class="shadow-effect">
                  <img class="img-circle" src="/assets/association-temoin.jpg" alt="Directeur ATPN">
                  <p>"HelpHub nous a permis de trouver de nouveaux donateurs et de financer intégralement 3 projets cette année. Une plateforme indispensable pour les associations comme la nôtre."</p>
                </div>
                <div class="testimonial-name">Directeur ATPN</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Faire un don -->
    <section id="donate" class="container">
      <div class="divisionin"></div>
      <div class="content-text">
        <h2>Faire un don</h2>
        <p>Chaque contribution compte</p>
      </div>
      
      <div class="donation-options">
        <div class="donation-card">
          <h3>Don ponctuel</h3>
          <p>Soutenez un projet spécifique avec un don unique</p>
          <a href="/donate/one-time" class="btn btn-primary">Choisir</a>
        </div>
        
        <div class="donation-card">
          <h3>Don mensuel</h3>
          <p>Un soutien régulier pour un impact durable</p>
          <a href="/donate/monthly" class="btn btn-primary">Choisir</a>
        </div>
        
        <div class="donation-card">
          <h3>Entreprise</h3>
          <p>Engagez votre entreprise dans une démarche solidaire</p>
          <a href="/donate/business" class="btn btn-primary">Choisir</a>
        </div>
      </div>
    </section>

    <footer id="footer">    
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <h3>A propos</h3>
            <p>HelpHub est une plateforme de dons caritatifs qui connecte donateurs et associations depuis 2023.</p>
          </div>
          <div class="footer-section">
            <h3>Liens utiles</h3>
            <ul>
              <li><a href="/legal">Mentions légales</a></li>
              <li><a href="/privacy">Confidentialité</a></li>
              <li><a href="/faq">FAQ</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h3>Contact</h3>
            <p>contact@helphub.tn<br>+216 70 000 000</p>
          </div>
        </div>
        <p class="copyright">Copyright &copy; HelpHub <span id="datee"></span>, Tous droits réservés</p>
      </div>
    </footer>
    
    <div class="container mt-3">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/jquery.min.js"></script>
    <script src="/assets/owl.carousel.js"></script>
    <script src="/assets/js/script.js"></script>
    
    <script>
        // Typewriter effect
        const phrases = ["Soutenez des causes nobles", "Changez des vies", "Faites la différence"];
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        const typewriterElement = document.getElementById('typewriter');
        
        function typeWriter() {
            const currentPhrase = phrases[phraseIndex];
            
            if (isDeleting) {
                typewriterElement.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
            }
            
            if (!isDeleting && charIndex === currentPhrase.length) {
                isDeleting = true;
                setTimeout(typeWriter, 2000);
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                setTimeout(typeWriter, 500);
            } else {
                const speed = isDeleting ? 100 : 150;
                setTimeout(typeWriter, speed);
            }
        }
        
        typeWriter();
        document.getElementById('datee').textContent = new Date().getFullYear();
    </script>
</body>
</html>